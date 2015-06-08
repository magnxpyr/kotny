<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Admin;

/**
 * Class Routes
 * @package Admin
 */
class Routes {

    /**
     * @param \Phalcon\Mvc\Router $router
     */
    public function init($router)
    {
        $router->add('/admin', array(
            'module' => 'admin',
            'controller' => 'admin-index',
            'action' => 'index'
        ));

        $router->add('/admin/:module', array(
            'module' => 1,
            'controller' => 'admin-index',
            'action' => 'index'
        ));

        $router->add('/admin/:module/:controller', array(
            'module' => 1,
            'controller' => 2,
            'action' => 'index'
        ))->convert('controller', function($controller) {
            return "admin-$controller";
        });

        $router->add('/admin/:module/:controller/:action', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3
        ))->convert('controller', function($controller) {
            return "admin-$controller";
        });

        $router->add('/admin/:module/:controller/:action/:params', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ))->convert('controller', function($controller) {
            return "admin-$controller";
        });
    }
}