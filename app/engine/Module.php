<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

use Phalcon\Text;

abstract class Module {

    public function registerAutoloaders() {
    }

    public function registerServices($di) {
        $moduleName = Text::camelize($di['router']->getModuleName());

        //Attach a event listener to the dispatcher
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace($moduleName . '\Controllers');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(APP_PATH . 'modules/' . $moduleName . '/Views/');
    }
}