<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

use Phalcon\Text;

class Loader extends \Phalcon\Loader {

    public function init($namespaces) {
        // Phalcon loader
        $this->registerNamespaces(array_merge($namespaces->toArray(), array(
            'Phalcon' => APP_PATH . 'vendor/phalcon/incubator/Library/Phalcon/',
            'Engine' => APP_PATH . 'engine/'
        )));
        $this->register();


        // Composer loader
        require_once APP_PATH . 'vendor/autoload.php';
    }

    public function modulesConfig($modules_list)
    {
        $namespaces = array();
        $modules = array();
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $moduleName = $module;
                $module = Text::camelize($module);
                $namespaces[$module] = APP_PATH . "modules/$module";
                $module_path = APP_PATH . "modules/$module/Module.php";
                if(!file_exists($module_path)) {
                    $module_path = APP_PATH . 'engine/Module.php';
                }
                $modules[$moduleName] = array(
                    'className' => "$module\\Module",
                    'path' => $module_path
                );
            }
        }

        $modules_array = array(
            'loader' => array('namespaces' => $namespaces),
            'modules' => $modules,
        );

        return $modules_array;
    }
}