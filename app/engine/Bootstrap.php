<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */
class Bootstrap {

    public function run() {
        // Define internal variables
        define('MG_VERSION', '0.1.0');
        define('DEFAULT_THEME', 'default');
        define('THEMES_PATH', '../../../themes/'.DEFAULT_THEME.'/');

        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();

        // Load config file
        $config = require_once APP_PATH . 'config/config.php';
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
        $di->set('config', $config);

        // Registering the registry
        $registry = new \Phalcon\Registry();
        $di->set('registry', $registry);

        // Getting a request instance
        $request = new Phalcon\Http\Request();
        $di->set('request', $request);

        // Register routers with default behavior
        // Set 'false' to disable default behavior. After that define all routes or you get 404
        $router = new Phalcon\Mvc\Router();
        $router->removeExtraSlashes(true);
        $router->setDefaults(array(
            'module' => 'core',
            'controller' => 'index',
            'action' => 'index'
        ));
/*
        $router->notFound(array(
            'module' => 'core',
            'controller' => 'error',
            'action' => 'show404'
        ));
*/

        foreach($modulesRoutes as $routeClass) {
            $route = new $routeClass;
            $route->init($router);
        }
        $di->set('router', $router);

        // Generate urls
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri($config->app->baseUri);
        $url->setBasePath(ROOT_PATH);
        $di->set('url', $url);

        // Setting up the view component
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

        $view->registerEngines(array(
            '.volt' => $volt,
            '.phtml' => $phtml
        ));
        $di->setShared('view', $view);


        // Start the session from file
        $session = new \Phalcon\Session\Adapter\Files();
        $session->set('auth', array(
            'name' => 'Guests'
        ));
        $session->start();
        $di->setShared('session', $session);


        // Connect to db
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname
        ));
        $di->setShared('db', $db);

        // Register ACL to DI
        $acl = new \Phalcon\Acl\Adapter\Database(array(
            'db' => $db,
            'roles' => 'roles',
            'rolesInherits' => 'roles_inherits',
            'resources' => 'resources',
            'resourcesAccesses' => 'resources_accesses',
            'accessList' => 'access_list',
        ));
        $di->set('acl', $acl);

        $response = new \Phalcon\Http\Response();
        $di->set('response', $response);


        //Obtain the standard eventsManager from the DI
        $eventsManager = new \Phalcon\Events\Manager();

        //Registering a dispatcher
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        // Attach the Security plugin
        $eventsManager->attach('dispatch', new \Engine\Plugins\Security());
        // Attach the Error handler
        $eventsManager->attach('dispatch', new \Engine\Plugins\ErrorHandler());

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

        $translator = new \Phalcon\Translate\Adapter\NativeArray(array('content' => require $langFile));

        $di->setShared('t', $translator);


        // Set cache
        $cacheFrontend = new \Phalcon\Cache\Frontend\Data(array(
            "lifetime" => 172800,
            "prefix" => '_',
        ));

        $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, array(
            "cacheDir" => ROOT_PATH . "/cache/backend/"
        ));

        $di->set('cache', $cache);
        $di->set('modelsCache', $cache);

        // If the configuration specify the use of metadata adapter use it or use memory otherwise
        $di->set('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\MetaData\Memory();
        });

        // Register assets that will be loaded in every page
        $di->setShared('assets', function() {
            return new \Phalcon\Assets\Manager();
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

        if($config->app->development) {
            // Load development options
            new \Engine\Development($di);
        }

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