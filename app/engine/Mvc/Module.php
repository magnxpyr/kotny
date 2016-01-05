<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Text;

/**
 * Class Module
 * @package Engine
 */
abstract class Module implements ModuleDefinitionInterface {

    /**
     * Registers an autoloader related to the module
     *
     * @param \Phalcon\DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null) {
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param \Phalcon\DiInterface $di
     */
    public function registerServices(DiInterface $di) {
        $moduleName = Text::camelize($di['router']->getModuleName());

        //Attach a event listener to the dispatcher
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace($moduleName . '\Controllers');

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir(APP_PATH . 'modules/' . $moduleName . '/Views/');
    }
}