<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

class Bootstrap {

    public function run() {
        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();


        $config = include 'config/config.php';
        $di->set('config', $config);
        $loader = new \Phalcon\Loader();

        // Registering directories taken from the configuration file
        $loader->registerDirs(
            array(
                $config->application->controllersDir,
                $config->application->modelsDir,
                $config->application->vendorDir
            )
        )->register();

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
                //    '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
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
    }
}