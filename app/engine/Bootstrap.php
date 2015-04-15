<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

class Bootstrap {

    public function run() {
        // Define internal variables
        define('VIEW_PATH', '../../../themes/default/');
        $voltOptions = array();

        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();

        // Load config file
        $config = require_once APP_PATH . 'config/config.php';
        // Load modules
        $modules_list = require_once APP_PATH . 'config/modules.php';
        // Load loader
        require_once APP_PATH . 'engine/Loader.php';
        $loader = new \Engine\Loader();
        $modules_config = $loader->modulesConfig($modules_list);
        $config = new \Phalcon\Config(array_merge_recursive($config, $modules_config));
        $loader->init($config->loader->namespaces);
        $di->set('config', $config);

        // Load development options
        if($config->app->development) {
            new \Engine\Development();

            // Prevent caching annoyances
            $voltOptions['compileAlways'] = true;
        }

        // Registering the registry
        $registry = new \Phalcon\Registry();
        $di->set('registry', $registry);

        // Getting a request instance
        $request = new Phalcon\Http\Request();
        $di->set('request', $request);

        // Register routers with default behavior
        // Set 'false' to disable default behavior and define all routes or you get 404
        $router = new Phalcon\Mvc\Router();
        $router->setDefaultModule("Cms");
        $router->removeExtraSlashes(true);
        /*
        $router->notFound(array(
            'controller'    => 'error',
            'action'        => 'show404'
        ));
        */
/*
        $router->add('/:controller/:action', array(
            'module'        => 'Cms',
            'controller'    => 1,
            'action'        => 2
        ));
*/
        if(\Phalcon\Text::startsWith($request->get('_url'), '/admin')) {
            $uri = explode('/', $request->get('_url'));
            $router->add('/admin/:module', array(
                'module' => ucfirst($uri[2]),
                'controller' => 'admin_index',
                'action' => 'index'
            ));
            if(count($uri) > 3) {
                $router->add('/admin/:module/:controller', array(
                    'module' => ucfirst($uri[2]),
                    'controller' => 'admin_' . $uri[3],
                    'action' => 'index'
                ));
                $router->add('/admin/:module/:controller/:action', array(
                    'module' => ucfirst($uri[2]),
                    'controller' => 'admin_' . $uri[3],
                    'action' => 3
                ));
                $router->add('/admin/:module/:controller/:action/:params', array(
                    'module' => ucfirst($uri[2]),
                    'controller' => 'admin_' . $uri[3],
                    'action' => 3,
                    'params' => 4
                ));
            }
        }
        /*
        $router->add('/admin', array(
            'module'        => 'Admin',
            'controller'    => 'index',
            'action'        => 'index'
        ));
        $router->add('/admin/:controller/:action', array(
            'module'        => 'Admin',
            'controller'    => 1,
            'action'        => 2
        ));
        $router->add('/admin/:controller/:action/:params', array(
            'module'        => 'Admin',
            'controller'    => 1,
            'action'        => 2,
            'params'        => 3
        ));
        */
        $router->add('/user', array(
            'module'        => 'User',
            'controller'    => 'users',
            'action'        => 'index'
        ));
        $router->add('/user/:action', array(
            'module'        => 'User',
            'controller'    => 'users',
            'action'        => 1
        ));
        $router->add('/devtools', array(
            'module'        => 'DevTools',
            'controller'    => 'index',
            'action'        => 'index'
        ));
        $router->add('/devtools/:controller', array(
            'module'        => 'DevTools',
            'controller'    => 1,
            'action'        => 'index'
        ));
        $router->add('/devtools/:controller/:action', array(
            'module'        => 'DevTools',
            'controller'    => 1,
            'action'        => 2
        ));
        $di->set('router', $router);

        // Generate urls
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri($config->app->baseUri);
        $url->setBasePath(ROOT_PATH);
        $di->set('url', $url);

        // Setting up the view component
        $view = new \Phalcon\Mvc\View();
        $view->setLayoutsDir(VIEW_PATH . 'layouts/');
        $view->setPartialsDir(VIEW_PATH . 'partials/');
        $view->setMainView(VIEW_PATH . 'default');
        $view->setLayout('default');

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $voltOptions['compiledPath'] = $config->app->cacheDir;
        $voltOptions['compiledSeparator'] = '_';
        $volt->setOptions($voltOptions);
        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);

        $view->registerEngines(array(
            '.volt' => $volt,
            '.phtml' => $phtml
        ));
        $di->set('view', $view);


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

        $di->set('dispatcher', $dispatcher);


        // Get the language from session
        $language = $session->get("lang");
        if (!$language) {
            // Ask browser what is the best language
            $language = $di->getShared('request')->getBestLanguage();
        }
        $lang_file = APP_PATH . "messages/" . $language . ".php";

        //Check if we have a translation file for that lang
        if (!file_exists($lang_file)) {
            // Fallback to default
            $lang_file = APP_PATH . "messages/en.php";
        }

        $translator = new \Phalcon\Translate\Adapter\NativeArray(array('content' => require $lang_file));

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

        // Register Tags
        $tag = new \Phalcon\Tag();
        switch($config->app->site_name_location) {
            case 1:
                $tag->prependTitle($config->app->site_name . ' | ');
                break;
            case 2:
                $tag->appendTitle('|' . $config->app->site_name);
                break;
        }

        // Register assets that will be loaded in every page
        $assets = new \Phalcon\Assets\Manager();
        $assets->collection('header-js')
            ->addJs('vendor/jquery/jquery-2.1.3.min.js')
            ->addJs('vendor/jquery/jquery-ui.min.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js');
        $assets->collection('header-css')
            ->addCss('vendor/jquery/jquery-ui.min.css')
            ->addCss('vendor/bootstrap/css/bootstrap.min.css');

        $di->setShared('assets', $assets);


        // Register the flash service with custom CSS classes
        $flash = new \Phalcon\Flash\Session(array(
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning',
            'error'   => 'alert alert-danger'
        ));
        $di->setShared('flash', $flash);

        // Handle the request
        $application = new \Phalcon\Mvc\Application($di);
        $application->registerModules($config->modules->toArray());
        $application->setDI($di);

        // Render
        echo $application->handle()->getContent();
    }
}