<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

namespace Tools\Helper;

use Phalcon\Di;
use Tools\Controllers\ControllerBase;

class Tools extends ControllerBase {
    /**
     * @var \Phalcon\DI
     */
    private static $di;

    /**
     * Optional IP address for securing Phalcon Developers Tools area
     *
     * @var string
     */
    private static $ip = null;

    /**
     * Navigation
     *
     * @var array
     */
    private static $options = array(
        'index' => array(
            'caption' => 'Home',
            'options' => array(
                'index' => array(
                    'caption' => 'Welcome'
                )
            )
        ),
        'module' => array(
            'caption' => 'Module',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
                )
            )
        ),
        'controllers' => array(
            'caption' => 'Controllers',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate',
                ),
                'list' => array(
                    'caption' => 'List',
                )
            )
        ),
        'models' => array(
            'caption' => 'Models',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
                ),
                'list' => array(
                    'caption' => 'List',
                )
            )
        ),
        'scaffold' => array(
            'caption' => 'Scaffold',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
                )
            )
        ),
        'migrations' => array(
            'caption' => 'Migrations',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
                ),
                'run' => array(
                    'caption' => 'Run'
                )
            )
        )
    );

    /**
     * Print navigation menu of the given controller
     *
     * @param  string $controllerName
     * @return void
     */
    public static function getNavMenu($controllerName)
    {
        foreach (self::$options as $controller => $option) {
            $ref = self::generateUrl($controller);
            if ($controllerName == $controller) {
                echo '<a class="list-group-item active" href="' . $ref . '">' . $option['caption'] . '</a>' . PHP_EOL;
            } else {
                echo '<a class="list-group-item" href="' . $ref . '">' . $option['caption'] . '</a>' . PHP_EOL;
            }
        }
    }

    /**
     * Print menu of the given controller action
     *
     * @param  string $controllerName
     * @param  string $actionName
     * @return void
     */
    public static function getMenu($controllerName, $actionName)
    {
        $uri = self::getUrl()->get();

        foreach (self::$options[$controllerName]['options'] as $action => $option) {
            if ($actionName == $action) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }

            $ref = $uri . '/' . $controllerName . '/' . $action;
            echo '<a href="' . $ref . '">' . $option['caption'] . '</a></li>' . PHP_EOL;
        }
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Config
     */
    public static function getConfig()
    {
        return Di::getDefault()->getShared('config');
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Url
     */
    public static function getUrl()
    {
        return Di::getDefault()->getShared('url');
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Router
     */
    public static function getRouter()
    {
        return Di::getDefault()->getShared('router');
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Url
     */
    public static function getConnection()
    {
        return Di::getDefault()->getShared('db');
    }

    /**
     * Return a new url according to params
     *
     * @params string $controller
     * @params string $action
     * @params string $params
     * @return string
     */
    public static function generateUrl($controller, $action = 'index', $params = null) {
        $baseUri = self::getUrl()->get();
        $uriPath = self::getRouter()->getMatchedRoute()->getPattern();
        return str_replace(array('//', ':controller', ':action'),array('/', $controller, $action), $baseUri . $uriPath) . $params;
    }

    /**
     * Return an optional IP address for securing Phalcon Developers Tools area
     *
     * @return string
     */
    public static function getToolsIp()
    {
        return self::$ip;
    }
}