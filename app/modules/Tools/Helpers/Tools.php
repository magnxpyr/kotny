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

namespace Tools\Helpers;

use Phalcon\Di;
use Tools\Controllers\ControllerBase;

class Tools extends ControllerBase {

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
        'modules' => array(
            'caption' => 'Modules',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
                ),
                'list' => array(
                    'caption' => 'List'
                )
            )
        ),
        'controllers' => array(
            'caption' => 'Controllers',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate',
                )
            )
        ),
        'models' => array(
            'caption' => 'Models',
            'options' => array(
                'index' => array(
                    'caption' => 'Generate'
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
                echo '<a class="list-group-item active"';
            } else {
                echo '<a class="list-group-item"';
            }
            echo  ' href="' . $ref . '">' . $option['caption'] . '</a>' . PHP_EOL;
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
        foreach (self::$options[$controllerName]['options'] as $action => $option) {
            $ref = self::generateUrl($controllerName, $action);
            if ($actionName == $action) {
                echo '<li role="presentation" class="active"><a href="' . $ref . '">' . $option['caption'] . '</a></li>' . PHP_EOL;
            } else {
                echo '<li role="presentation"><a href="' . $ref . '">' . $option['caption'] . '</a></li>' . PHP_EOL;
            }
        }
    }

    /**
     * Return the path to modules directory
     *
     * @return string
     */
    public static function getModulesDir() {
        if(isset(self::getConfig()->tools)) {
            return self::getConfig()->tools->modulesDir;
        }
        return APP_PATH . 'modules/';
    }

    /**
     * Return an array with modules name
     *
     * @return array
     */
    public static function listModules() {
        $iterator = new \DirectoryIterator(self::getModulesDir());
        $modules = array();
        foreach($iterator as $fileinfo){
            if(!$fileinfo->isDot() && file_exists($fileinfo->getPathname() . '/Module.php')){
                $modules[] = $fileinfo->getFileName();
            }
        }
        return $modules;
    }

    /**
     * Return a string with all the module options
     *
     * @param string $selected
     * @return string
     */
    public static function listModuleOptions($selected = null) {
        $iterator = new \DirectoryIterator(self::getModulesDir());
        $options = null;
        foreach($iterator as $fileinfo){
            if(!$fileinfo->isDot() && file_exists($fileinfo->getPathname() . '/Module.php')){
                if($selected == $fileinfo->getFileName()) {
                    $options .= '<option value=' . $fileinfo->getFileName() . ' selected>' . $fileinfo->getFileName() . '</option>';
                } else {
                    $options .= '<option value=' . $fileinfo->getFileName() . '>' . $fileinfo->getFileName() . '</option>';
                }
            }
        }
        return $options;
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
     * @return \Phalcon\Mvc\Model
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
        return str_replace(array('//', ':controller', ':action', ':params'),array('/', $controller, $action, $params), $baseUri . $uriPath);
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

    public static function getCopyright() {
        return "/**\n* @copyright   2006 - 2015 Magnxpyr Network\n".
         "* @license     New BSD License; see LICENSE\n".
         "* @url         http://www.magnxpyr.com\n".
         "* @authors     Stefan Chiriac <stefan@magnxpyr.com>\n*/";
    }

    public static function getControllersDir() {
        return 'Controllers';
    }

    public static function getModelsDir() {
        return 'Models';
    }

    public static function getViewsDir() {
        return 'Views';
    }
}