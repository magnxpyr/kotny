<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 11.03.2015
 * Time: 14:37
 */

namespace Engine;


class Application extends \Phalcon\Mvc\Application {

    // Register the services here to make them general or register in the ModuleDefinition to make them module-specific
    protected function _registerServices()
    {
        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $di = new \Phalcon\DI\FactoryDefault();

        // load config file
        $config = require_once APP_PATH . 'config/config.php';
        $di->set('config', $config);

        $loader = new \Phalcon\Loader();
        // registering directories
        $loader->registerDirs(
            array(
                APP_PATH . 'engine/'
            )
        );
        // registering namespaces
    //    $loader->registerNamespaces($config->loader->namespaces->toArray());
        $loader->register();


        //Registering a router
        $di->set('router', function(){
            $router = new \Phalcon\Mvc\Router();
            $router->setDefaultModule("frontend");
            /*
            $router->add('/', array(
                'module' => 'frontend',
                'controller' => 'index',
                'action' => 'index',
            ));
            */
            return $router;
        });
        $this->setDI($di);
    }

    public function main()
    {
        try {
            $this->_registerServices();
            //Register the installed modules
            $this->registerModules(array(
                'frontend' => array(
                    'className' => 'Modules\Frontend\Module',
                    'path' => APP_PATH . 'modules/frontend/Module.php'
                )
            ));
            echo $this->handle()->getContent();
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }
}