<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
        ])->setName('core-home');

//        $router->add('/:controller', [
//            'controller' => 1,
//            'action' => 'index'
//        ])->setName('core-ca');
//
//        $router->add('/:controller/:action', [
//            'controller' => 1,
//            'action' => 2,
//        ])->setName('core-ca');
//
//        $router->add('/:controller/:action/:params', [
//            'controller' => 1,
//            'action' => 2,
//            'params' => 3,
//        ])->setName('core-cap');

        // Article
        $router->add('/([a-z]+)/([0-9]+)-([a-zA-Z0-9\-]+)', [
            'controller' => 'content',
            'action' => 'article',
            'category' => 1,
            'articleId' => 2,
            'articleAlias' => 3
        ])->setName('core-article');

        // Category
        // Category
        $router->add('/([a-z]+)', [
            'controller' => 'content',
            'action' => 'category',
            'category' => 1
        ])->setName('core-category');

        $router->add('/([a-z]+)/([0-9]+)', [
            'controller' => 'content',
            'action' => 'category',
            'category' => 1,
            'page' => 2
        ])->setName('core-categoryWithPage');

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

        // User
        $router->add('/user/([a-zA-Z0-9\-\.]+)', [
            'module' => 'core',
            'controller' => 'user',
            'action' => 1,
        ])->setName('user-home');
    }
}