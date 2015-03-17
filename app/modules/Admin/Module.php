<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Modules\Admin;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array(
            'Modules\Admin\Controllers' => __DIR__ . '/controllers/',
        ));
        $loader->register();
    }

    public function registerServices($di)
    {
        //Attach a event listener to the dispatcher
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Modules\Admin\Controllers\\');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/views/');

    }
}