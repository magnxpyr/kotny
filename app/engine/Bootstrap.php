<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

class Bootstrap {

    public function run() {
        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();

        // Load config file
        $config = require_once APP_PATH . 'config/config.php';
        $modules_list = require_once APP_PATH . 'config/modules.php';
        $modules_config = $this->modulesConfig($modules_list);
        $config = new \Phalcon\Config(array_merge_recursive($config, $modules_config));
        $di->set('config', $config);

        // Load pretty exceptions
        if($config->app->development) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require APP_PATH . 'vendor/phalcon/pretty-exceptions/loader.php';
        }

        // Registering directories
        $loader = new \Phalcon\Loader();
        //$loader->registerNamespaces($config->loader->namespaces->toArray());
        $loader->registerDirs(array(APP_PATH . 'engine/'));
        $loader->register();

        // Register routers
        $router = new Phalcon\Mvc\Router();
        $router->setDefaultModule("Admin");
        $di->set('router', $router);
/*
        // Generate urls
        $di->set('url', function () use ($config) {
            $url = new Phalcon\Mvc\Url();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        }, true);
*/

        // Setting up the view component
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(APP_PATH . 'views/');
        $view->setMainView('main');
        $view->setLayoutsDir(APP_PATH . 'views/layouts/');
        $view->setLayout('main');
        $view->setPartialsDir(APP_PATH . 'views/partials/');

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(array(
            'compiledPath' => $config->app->cacheDir,
            'compiledSeparator' => '_'
        ));
        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);

        $view->registerEngines(array(
            '.volt' => $volt,
            '.phtml' => $phtml
        ));
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        $di->set('view', $view);


        //Registering a dispatcher
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $di->set('dispatcher', $dispatcher);


        // Connect to db
        $di->set('db', function () use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                "charset" => $config->database->charset
            ));
        });

        $cacheFrontend = new \Phalcon\Cache\Frontend\Data(array(
            "lifetime" => 60,
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

        // Start the session from file
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $di->set('session', $session);

        // Handle the request
        $application = new \Phalcon\Mvc\Application($di);
        $application->registerModules($config->modules->toArray());
        $application->setDI($di);

        // Register the flash service with custom CSS classes
        $flash = new \Phalcon\Flash\Session(array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info'
        ));
        $di->set('flash', $flash);

        // Render
        echo $application->handle()->getContent();
    }

    public function modulesConfig($modules_list)
    {
        //    $namespaces = array();
        $modules = array();
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                //    $namespaces["Modules\\$module"] = APP_PATH . 'modules/' . $module;
                $modules[$module] = array(
                    'className' => "Modules\\$module\\Module",
                    'path' => APP_PATH . "modules/$module/Module.php"
                );
            }
        }

        $modules_array = array(
            //    'loader' => array('namespaces' => $namespaces),
            'modules' => $modules,
        );
        return $modules_array;
    }
}