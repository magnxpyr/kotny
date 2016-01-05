<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
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
        $router->add('/', [
            'controller' => 'index',
            'action' => 'index'
        ])->setName('core-ca');

        $router->add('/:controller', [
            'controller' => 1,
            'action' => 'index'
        ])->setName('core-ca');

        $router->add('/:controller/:action', [
            'controller' => 1,
            'action' => 2,
        ])->setName('core-ca');

        $router->add('/:controller/:action/:params', [
            'controller' => 1,
            'action' => 2,
            'params' => 3,
        ])->setName('core-cap');

        $router->add('/:module/:controller/:action/:params', [
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4,
        ])->setName('default-mcap');

        // Admin
        $router->add('/admin', array(
            'module' => 'core',
            'controller' => 'admin-index',
            'action' => 'index'
        ))->setName('admin-home');

        $router->add('/admin/:module/:controller/:action/:params', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ))->convert('controller', function($controller) {
            return "admin-$controller";
        })->setName('admin-mcap');
/*
        $router->add('/:username', [
            'module' => 'core',
            'controller' => 'user',
            'action' => 'index',
            'username' => 1
        ])->setName('user-home');
*/
    }
}