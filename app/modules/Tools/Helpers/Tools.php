<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */


namespace Tools\Helpers;

use Phalcon\Config;
use Phalcon\Di;
use Tools\Controllers\ControllerBase;

class Tools extends ControllerBase {

    /**
     * @var \Phalcon\Config
     */
    private static $_config;

    /**
     * @var \Phalcon\Config
     */
    private static $_toolsConfig;

    /**
     * @var \Phalcon\Mvc\Url
     */
    private static $_url;

    /**
     * @var \Phalcon\Mvc\Router
     */
    private static $_router;

    /**
     * @var \Phalcon\Mvc\Model
     */
    private static $_db;

    /**
     * Navigation
     *
     * @var array
     */
    private static $_options = array(
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
        foreach (self::$_options as $controller => $option) {
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
    public static function getMenu($controllerName, $actionName) {
        foreach (self::$_options[$controllerName]['options'] as $action => $option) {
            $ref = self::generateUrl($controllerName, $action);
            if ($actionName == $action) {
                echo '<li role="presentation" class="active"><a href="' . $ref . '">' . $option['caption'] . '</a></li>' . PHP_EOL;
            } else {
                echo '<li role="presentation"><a href="' . $ref . '">' . $option['caption'] . '</a></li>' . PHP_EOL;
            }
        }
    }

    /**
     * Tries to find the current configuration in the application
     *
     * @return mixed|\Phalcon\Config\Adapter\Ini
     * @throws \Exception
     */
    protected static function _getToolsConfig() {
        if(is_null(self::$_toolsConfig)) {
            $config = self::getConfig();
            if(!isset($config->tools))
                throw new \Exception ('Unable to find config file');
            if(is_string($config->tools))
                $config = new Config(require_once $config->tools);
            else
                $config = $config->tools;
            self::$_toolsConfig = $config;
        }

        return self::$_toolsConfig;
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Config
     */
    public static function getConfig() {
        if(is_null(self::$_config))
            self::$_config = Di::getDefault()->getShared('config');
        return self::$_config;
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Url
     */
    public static function getUrl() {
        if(is_null(self::$_url))
            self::$_url = Di::getDefault()->getShared('url');
        return self::$_url;
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Router
     */
    public static function getRouter() {
        if(is_null(self::$_router))
            self::$_router = Di::getDefault()->getShared('router');
        return self::$_router;
    }

    /**
     * Return the config object in the services container
     *
     * @return \Phalcon\Mvc\Model
     */
    public static function getConnection() {
        if(is_null(self::$_db))
            self::$_db = Di::getDefault()->getShared('db');
        return self::$_db;
    }

    /**
     * Return a new url according to params
     *
     * @param string $controller
     * @param string $action
     * @param string $params
     * @return string
     */
    public static function generateUrl($controller, $action = 'index', $params = null) {
        $baseUri = self::getUrl()->get();
        $uriPath = self::getRouter()->getMatchedRoute()->getPattern();
        return str_replace(array('//', ':controller', ':action', ':params'),array('/', $controller, $action, $params), $baseUri . $uriPath);
    }

    /**
     * Return an array with modules name
     *
     * @return array
     */
    public static function listModules() {
        $iterator = new \DirectoryIterator(self::getModulesPath());
        $modules = array();
        foreach($iterator as $fileinfo){
            if(!$fileinfo->isDot() && file_exists($fileinfo->getPathname() . '/Module.php')){
                $modules[] = $fileinfo->getFileName();
            }
        }
        return $modules;
    }

    /**
     * Return the modules input with all modules
     *
     * @param string $selected
     * @return string
     */
    public static function renderModulesInput($selected = null) {
        $input = '<label class="control-label" for="name">Module</label>
                    <input list="module" name="module" value="' . $selected . '" class="form-control">
                    <datalist id="module">';

        $iterator = new \DirectoryIterator(self::getModulesPath());
        $options = null;
        foreach($iterator as $fileinfo){
            if(!$fileinfo->isDot() && file_exists($fileinfo->getPathname() . '/Module.php')){
                $input .= '<option value=' . $fileinfo->getFileName() . '>';
            }
        }

        $input .= '</datalist>';

        return $input;
    }

    /**
     * Return the base controllers input
     *
     * @return string
     */
    public static function renderControllersInput() {
        $classes = [];
        if(isset(self::_getToolsConfig()->baseController)) {
            $classes =  self::_getToolsConfig()->baseController;
        }

        $input = '<label class="control-label" for="name">Base Class</label>
                    <input list="baseClass" name="baseClass" ' . (!empty($classes) ? 'value="' . current($classes) . '"' : '') .' class="form-control">
                    <datalist id="baseClass">';

        foreach($classes as $class) {
            $input .= '<option value=' . $class . '>';
        }

        $input .= '</datalist>';

        return $input;
    }

    /**
     * Return the base models input
     *
     * @return string
     */
    public static function renderModelsInput() {
        $classes = [];
        if(isset(self::_getToolsConfig()->baseModel)) {
            $classes =  self::_getToolsConfig()->baseModel;
        }

        $input = '<label class="control-label" for="name">Base Class</label>
                    <input list="baseClass" name="baseClass" ' . (!empty($classes) ? 'value="' . current($classes) . '"' : '') .' class="form-control sticky-value">
                    <datalist id="baseClass">';

        foreach($classes as $class) {
            $input .= '<option value=' . $class . '>';
        }

        $input .= '</datalist>';

        return $input;
    }

    /**
     * Return an optional IP address for securing Phalcon Developers Tools area
     * @return string
     */
    public static function getToolsIp() {
        if(isset(self::_getToolsConfig()->allow)) {
            return self::_getToolsConfig()->allow;
        }
        return '';
    }

    /**
     * Return the path to modules directory
     * @return string
     * @throws \Exception
     */
    public static function getModulesPath() {
        if(isset(self::_getToolsConfig()->modulesPath)) {
            return self::_getToolsConfig()->modulesPath;
        }
        throw new \Exception('Modules path is not defined');
    }

    /**
     * Return the Controller directory
     * @return string
     */
    public static function getControllersDir() {
        if(isset(self::_getToolsConfig()->controllersDir)) {
            return self::_getToolsConfig()->controllersDir;
        }

        return 'Controllers';
    }

    /**
     * Return the Model directory
     * @return string
     */
    public static function getModelsDir() {
        if(isset(self::_getToolsConfig()->modelsDir)) {
            return self::_getToolsConfig()->modelsDir;
        }
        return 'Models';
    }

    /**
     * Return the View directory
     * @return string
     */
    public static function getViewsDir() {
        if(isset(self::_getToolsConfig()->viewsDir)) {
            return self::_getToolsConfig()->viewsDir;
        }
        return 'Views';
    }

    /**
     * Return the copyright header
     * @return string
     */
    public static function getCopyright() {
        if(isset(self::_getToolsConfig()->copyright)) {
            return self::_getToolsConfig()->copyright;
        }
        return '';
    }
}