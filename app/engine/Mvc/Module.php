<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Engine\Meta;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Text;

/**
 * Class Module
 * @package Engine\Mvc
 */
class Module implements ModuleDefinitionInterface
{
    use Meta;

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
        $dispatcher->setDefaultNamespace("Module\\$moduleName\\Controllers");

        //Registering the view component
        $view = $di->get('view');
        $view->setViewsDir("$moduleName/Views/");
    }
}