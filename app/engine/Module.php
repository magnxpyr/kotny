<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Module {

    public function registerAutoloaders() {
    }

    public function registerServices($di)
    {
        //Attach a event listener to the dispatcher
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Modules\\' . $di['router']->getModuleName() . '\Controllers');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(APP_PATH . 'modules/' . $di['router']->getModuleName() . '/Views/');
    }
}