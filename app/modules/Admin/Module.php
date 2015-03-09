<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Modules\Admin;


use Phalcon\Mvc\Dispatcher,
    Phalcon\Loader,
    Phalcon\Mvc\View;

class Module {

    // Register specific autoloader for the module
    public function registerAutoloaders() {

    }

    // Register specific services for the module
    public function registerServices($di) {

        // Registering a dispatcher
        $di->set('dispatches', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MG\Admin\Controllers');
            return $dispatcher;
        });

        // Registering the view component
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views');
            return $view;
        });
    }

}