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

        // load config file
        $config = require_once APP_PATH . 'config/config.php';
        $di->set('config', $config);

        $loader = new \Phalcon\Loader();
 //       $loader->registerNamespaces($config->loader->namespaces->toArray());
/*
        $loader->registerNamespaces(
            array(
                'Modules\Admin' => APP_PATH . 'modules/Admin',
                'Modules\Admin\Controllers' => APP_PATH . 'modules/Admin/controllers'
            )
        );
*/
        // Registering directories taken from the configuration file
        $loader->registerDirs(
            array(
                $config->application->engineDir
            )
        );
        $loader->register();
        print_r($loader);

        $di->set('router', function () {

            $router = new Phalcon\Mvc\Router();

            $router->setDefaultModule("Admin");

            $router->add("/", array(
                'module'     => 'Admin',
                'controller' => 'index',
                'action'     => 'index',
            ));

            return $router;
        });

        //print_r($loader);

        // Generate urls
        $di->set('url', function () use ($config) {
            $url = new Phalcon\Mvc\Url();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        }, true);

        // Setting up the view component
        $di->set('view', function () use ($config) {

            $view = new \Phalcon\Mvc\View();

            $view->setViewsDir($config->application->viewsDir);

            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {

                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

                    $volt->setOptions(array(
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_'
                    ));

                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));

            return $view;
        }, true);

        // connect to db
        $di->set('db', function () use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                "charset" => $config->database->charset
            ));
        });

        // If the configuration specify the use of metadata adapter use it or use memory otherwise
        $di->set('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\MetaData\Memory();
        });

        // Start the session from file
        $di->set('session', function () {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();

            return $session;
        });

        // Handle the request
        $application = new \Phalcon\Mvc\Application($di);

        //$application->registerModules($config->modules->toArray());

        $application->registerModules(
            array(
                'frontend' => array(
                    'className' => 'Admin\Module',
                    'path'      => APP_PATH . 'modules/Admin/Module.php'
                )
            )
        );

        $application->setDI($di);
        echo $application->handle()->getContent();
       // $this->dispatcher($di);
    }

    private function dispatcher($di) {
        // Get the 'router' service
        $router = $di['router'];

        $router->handle();

        $view = $di['view'];

        $dispatcher = $di['dispatcher'];

        // Pass the processed router parameters to the dispatcher
        $dispatcher->setModuleName($router->getModuleName());
        $dispatcher->setControllerName($router->getControllerName());
        $dispatcher->setActionName($router->getActionName());
        $dispatcher->setParams($router->getParams());

        $ModuleClassName = \Phalcon\Text::camelize($router->getModuleName()) . '\Module';
        if (class_exists('Admin\Module')) {
            echo 'exist...';
            $module = new $ModuleClassName;
            $module->registerAutoloaders();
            $module->registerServices($di);
        }

        // Start the view
        $view->start();

        // Dispatch the request
        $dispatcher->dispatch();

        // Render the related views
        $view->render(
            $dispatcher->getControllerName(),
            $dispatcher->getActionName(),
            $dispatcher->getParams()
        );

        // Finish the view
        $view->finish();

        $response = $di['response'];

        // Pass the output of the view to the response
        $response->setContent($view->getContent());

        // Send the request headers
        $response->sendHeaders();

        // Print the response
        echo $response->getContent();
    }
}