<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Modules\Frontend;

use Phalcon\Mvc\Dispatcher,
    Phalcon\Loader,
    Phalcon\Mvc\View;
/*
class Module {

    // Register specific autoloader for the module
    public function registerAutoloaders() {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Modules\Admin\Controllers' => APP_PATH . 'Admin/controllers/'
        ));
        $loader->register();
    }

    // Register specific services for the module
    public function registerServices($di) {
        // Registering a dispatcher
        $di->set('dispatches', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Modules\Admin\Controllers');
            return $dispatcher;
        });

        // Registering the view component
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');
            return $view;
        });
    }
*/
/*
    public function registerServices($di) {

        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Modules\Admin\Controllers');
        $di->set('dispatcher', $dispatcher);

        //Setting up the view component
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/views/');
    }
*/
//}


class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Modules\Frontend\Controllers' => APP_PATH . 'modules/frontend/controllers/',
        ));
        $loader->register();
    }
    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    public function registerServices($di)
    {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            //Attach a event listener to the dispatcher

            $dispatcher->setDefaultNamespace('Modules\Frontend\Controllers\\');
            return $dispatcher;
        });
        //Registering the view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(APP_PATH . 'modules/frontend/views/');
            $view->registerEngines(array(
                '.volt' => function ($view, $di) {

                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

                    $volt->setOptions(array(
                        'compiledPath' => APP_PATH . 'cache/',
                        'compiledSeparator' => '_'
                    ));

                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));
            return $view;
        });
    }
}