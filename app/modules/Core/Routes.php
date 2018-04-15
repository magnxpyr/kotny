<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core;

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
        ])->setName('core-homepage');

        // Article
        $router->add('/([a-z]+)/([0-9]+)-([a-zA-Z0-9\-]+)', [
            'controller' => 'content',
            'action' => 'article',
            'category' => 1,
            'articleId' => 2,
            'articleAlias' => 3
        ])->setName('core-article');

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
        ])->setName('core-category-pagination');

        // Articles
        $router->add('/articles/([0-9]+)', [
            'controller' => 'content',
            'action' => 'category',
            'category' => null,
            'page' => 1
        ])->setName('core-articles');


        $router->add('/articles', [
            'controller' => 'content',
            'action' => 'category',
        ])->setName('core-articles');

        $router->add('/:module/:controller/:action/:params', [
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4,
        ])->setName('core-default');

        // Admin
        $router->add('/admin', array(
            'module' => 'core',
            'controller' => 'admin-index',
            'action' => 'index'
        ))->setName('core-admin-home');

        $router->add('/admin/:module/:controller/:action/:params', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ))->convert('controller', function($controller) {
            return "admin-$controller";
        })->setName('core-admin-default');

        // User
        $router->add('/user/([a-zA-Z0-9\-\.]+)', [
            'module' => 'core',
            'controller' => 'user',
            'action' => 1,
        ])->setName('core-user-home');
    }
}