<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Admin;

class Routes
{
    public function init($router)
    {
        $router->add('/admin', array(
            'module'     => 'Admin',
            'controller' => 'index',
            'action'     => 'index',
        ));
        return $router;
    }
}