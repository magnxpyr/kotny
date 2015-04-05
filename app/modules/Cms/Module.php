<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Modules\Cms;

class Module
{
    public function registerAutoloaders() {
    }

    public function registerServices($di) {
        //Attach a event listener to the dispatcher
        $di->get('dispatcher')->setDefaultNamespace('Modules\Cms\Controller');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}