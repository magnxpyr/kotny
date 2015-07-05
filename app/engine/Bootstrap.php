<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

use Phalcon\Events\Event;
use Phalcon\Dispatcher;

class Bootstrap
{
    public function run()
    {
        // Define internal variables
        define('MG_VERSION', '0.1.0');
        define('DEFAULT_THEME', 'default');
        define('THEMES_PATH', '../../../themes/' . DEFAULT_THEME . '/');

        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();

        // Load config file
        $config = require_once APP_PATH . 'config/config.php';
        if ($config['app']['development']) {
            if (is_file(APP_PATH . 'config/development/config.php')) {
                $config = require_once APP_PATH . 'config/development/config.php';
            }

            // Load development options
        //    new \Engine\Development($di);

        }

        // set cookies time
        $config['app']['cookie']['expire'] = time() + $config['app']['cookie']['expire'];

        // Load modules
        $modulesList = require_once APP_PATH . 'config/modules.php';
        // Load loader
        require_once APP_PATH . 'engine/Loader.php';
        $loader = new \Engine\Loader();
        $modulesConfig = $loader->modulesConfig($modulesList);
        $modulesRoutes = $modulesConfig['routes'];
        unset($modulesConfig['routes']);
        $config = new \Phalcon\Config(array_merge_recursive($config, $modulesConfig));
        $loader->init($config->loader->namespaces);
        $di->setShared('config', $config);

        // Registering the registry
        $registry = new \Phalcon\Registry();
        $di->setShared('registry', $registry);

        // Getting a request instance
        $request = new Phalcon\Http\Request();
        $di->setShared('request', $request);

        // Register routers with default behavior
        // Set 'false' to disable default behavior. After that define all routes or you get 404
        $router = new Phalcon\Mvc\Router(false);
        $router->removeExtraSlashes(true);
        $router->setDefaults([
            'module' => 'core',
            'controller' => 'index',
            'action' => 'index'
        ]);
/*
        $router->notFound([
            'module' => 'core',
            'controller' => 'error',
            'action' => 'show404'
        ]);
*/

        foreach($modulesRoutes as $routeClass) {
            $route = new $routeClass;
            $route->init($router);
        }
        $di->setShared('router', $router);

        // Generate urls
        $url = new \Engine\Url();
        $url->setBaseUri($config->app->baseUri);
        $url->setBasePath(ROOT_PATH);
        $di->setShared('url', $url);

        // Setting up the view component
        $di->setShared('view', function() use ($config, $di) {
            $view = new \Phalcon\Mvc\View();
            $view->setLayoutsDir(THEMES_PATH . 'layouts/');
            $view->setPartialsDir(THEMES_PATH . 'partials/');
            $view->setMainView(THEMES_PATH . 'index');
            $view->setLayout('default');

            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            if($config->app->development) {
                // Prevent caching annoyances
                $voltOptions['compileAlways'] = true;
            }
            $voltOptions['compiledPath'] = $config->app->cacheDir . 'volt/';
            $voltOptions['compiledSeparator'] = '_';
            $volt->setOptions($voltOptions);
            $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);

            $view->registerEngines([
                '.volt' => $volt,
                '.phtml' => $phtml
            ]);
            return $view;
        });

        // Setting up the widget view component
        $di->set('viewWidget', function() use ($config, $di) {
            $view = new \Phalcon\Mvc\View();
            $view->setLayoutsDir(THEMES_PATH . 'layouts/');
            $view->setLayout('widget');
            $view->disableLevel([
                \Phalcon\Mvc\View::LEVEL_AFTER_TEMPLATE => true,
                \Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT => true
            ]);

            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            if($config->app->development) {
                // Prevent caching annoyances
                $voltOptions['compileAlways'] = true;
            }
            $voltOptions['compiledPath'] = $config->app->cacheDir . 'volt/';
            $voltOptions['compiledSeparator'] = 'widget_';
            $volt->setOptions($voltOptions);
            $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);

            $view->registerEngines([
                '.volt' => $volt,
                '.phtml' => $phtml
            ]);
            return $view;
        });


        // Start the session from file
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $di->setShared('session', $session);

        // Set auth in di
        $di->setShared('auth', function() {
            return new \Engine\Plugins\Auth();
        });


        // Connect to db
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql([
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname
        ]);
        $di->setShared('db', $db);

        // Register ACL to DI
        $di->setShared('acl', function () use ($config, $db) {
            switch($config->app->aclAdapter) {
                case 'memory':
                    $aclConfig = new \Phalcon\Config(include APP_PATH . 'config/acl.php');
                    $factory = new \Phalcon\Acl\Factory\Memory();
                    return $factory->create($aclConfig);
                case 'database':
                    return new \Phalcon\Acl\Adapter\Database([
                        'db' => $db,
                        'roles' => 'roles',
                        'rolesInherits' => 'roles_inherits',
                        'resources' => 'resources',
                        'resourcesAccesses' => 'resources_accesses',
                        'accessList' => 'access_list',
                    ]);
            }
        });

        $response = new \Phalcon\Http\Response();
        $di->setShared('response', $response);


        //Obtain the standard eventsManager from the DI
        $eventsManager = new \Phalcon\Events\Manager();

        //Registering a dispatcher
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        // Attach the Security plugin
        $eventsManager->attach('dispatch', new \Engine\Plugins\AclHandler());
        // Attach the Error handler
        $eventsManager->attach('dispatch', new \Engine\Plugins\ErrorHandler());

        $eventsManager->attach('dispatch:beforeDispatchLoop', function(\Phalcon\Events\Event $event, \Phalcon\Dispatcher $dispatcher) {
            $dispatcher->setActionName(lcfirst(\Phalcon\Text::camelize($dispatcher->getActionName())));
        });

        //Bind the EventsManager to the Dispatcher
        $dispatcher->setEventsManager($eventsManager);

        $di->setShared('dispatcher', $dispatcher);


        // Get the language from session
        $language = $session->get("lang");
        if (!$language) {
            // Ask browser what is the best language
            $language = $request->getBestLanguage();
        }
        $langFile = APP_PATH . "messages/" . $language . ".php";

        //Check if we have a translation file for that lang
        if (!file_exists($langFile)) {
            // Fallback to default
            $langFile = APP_PATH . "messages/en-US.php";
        }

        $translator = new \Phalcon\Translate\Adapter\NativeArray(['content' => require $langFile]);

        $di->setShared('t', $translator);

        // Set up crypt service
        $di->setShared('crypt', function() use ($config) {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->app->cryptKey);
            return $crypt;
        });

        //  Set security options
        $di->setShared('security', function() {
            $security = new \Engine\Security();
            $security->setRandomBytes(32);
            $security->setWorkFactor(12);
            return $security;
        });


        // Set cache
        $cacheFrontend = new \Phalcon\Cache\Frontend\Data([
            "lifetime" => 172800,
            "prefix" => '_',
        ]);

        $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, [
            "cacheDir" => ROOT_PATH . "/cache/backend/"
        ]);

        $di->setShared('cache', $cache);
        $di->setShared('modelsCache', $cache);

        // If the configuration specify the use of metadata adapter use it or use memory otherwise
        $di->setShared('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\MetaData\Memory();
        });

        // Register assets that will be loaded in every page
        $di->setShared('assets', function() {
            return new \Phalcon\Assets\Manager();
        });

        // Register mail service
        $di->set('mail', function() use ($config) {
            $settings = [
                'from' => $config->mail->from->toArray(),
                'driver' => $config->mail->driver,
                'viewsDir' => APP_PATH . 'themes/' . DEFAULT_THEME . '/emails/'
            ];
            switch($config->mail->driver) {
                case 'sendmail':
                    $settings['sendmail'] = $config->mail->sendmail;
                    break;
                case 'smtp':
                    $settings = array_merge($settings, $config->mail->smtp->toArray());
                    break;
            }
            return new \Phalcon\Mailer\Manager($settings);
        });

        // Register the flash service with custom CSS classes
        $flash = [
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning',
            'error'   => 'alert alert-danger'
        ];

        $di->setShared('flash', function() use ($flash) {
            return new \Phalcon\Flash\Direct($flash);
        });

        $di->setShared('flashSession', function() use ($flash) {
            return new \Phalcon\Flash\Session($flash);
        });


        // Handle the request
        $application = new \Phalcon\Mvc\Application($di);
        $application->registerModules($config->modules->toArray());
        $application->setDI($di);
        if ($request->isAjax()) {
            $return = new \stdClass();
            $return->html = $application->view->getContent();
            $return->success = true;

            if ($request->getHeader(404) || $request->getHeader(503)) {
                $return->success = false;
            }
            $application->response->setContentType('application/json', 'UTF-8');
            $application->response->setContent(json_encode($return));
        }

        // Render
        echo $application->handle()->getContent();
    }
}