<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Phalcon\Text;

/**
 * Class Loader
 * @package Engine
 */
class Loader extends \Phalcon\Loader {

    public function init($namespaces) {
        // Phalcon loader
        $this->registerNamespaces(array_merge($namespaces->toArray(), array(
            'Phalcon' => APP_PATH . 'vendor/phalcon/incubator/Library/Phalcon/',
            'Engine' => APP_PATH . 'engine/',
            'Widget' => APP_PATH . 'widgets/'
        )));
        $this->register();

        // Composer loader
        require_once APP_PATH . 'vendor/autoload.php';
    }

    public function modulesConfig($modules_list) {
        $namespaces = array();
        $modules = array();
        $routes = array();
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces[$module] = APP_PATH . "modules/$module";
                $modulePath = APP_PATH . "modules/$module/Module.php";
                $routePath = APP_PATH . "modules/$module/Routes.php";
                if(file_exists($routePath)) {
                    $routes[] = "$module\\Routes";
                }
                /*
                if(!file_exists($module_path)) {
                    $module_path = APP_PATH . 'engine/Module.php';
                }
                */
                $modules[Text::uncamelize($module)] = array(
                    'className' => "$module\\Module",
                    'path' => $modulePath
                );
            }
        }

        $modules_array = array(
            'loader' => array('namespaces' => $namespaces),
            'modules' => $modules,
            'routes' => $routes
        );

        return $modules_array;
    }
}