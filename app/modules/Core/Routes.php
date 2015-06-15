<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core;

/**
 * Class Routes
 * @package Core
 */
class Routes {

    /**
     * @param \Phalcon\Mvc\Router $router
     */
    public function init($router)
    {
        $router->add('/:controller/:action', [
            'controller' => 1,
            'action' => 2,
            'params' => 3,
        ])->setName('core-ca');

        $router->add('/:controller/:action/:params', [
            'controller' => 1,
            'action' => 2,
            'params' => 3,
        ])->setName('core-cap');

        $router->add('/:username', [
            'module' => 'core',
            'controller' => 'user',
            'action' => 'index',
            'username' => 1
        ])->setName('user-home');
    }
}