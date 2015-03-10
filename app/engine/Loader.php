<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;
//use Phalcon\Text;

class Modules {

    public function modulesConfig($modules_list)
    {
        $namespaces = array();
        $modules = array();
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces[$module] = APP_PATH . 'modules/' . $module;
            //    $simple = Text::uncamelize($module);
            //    $simple = str_replace('_', '-', $simple);
                $modules[$module] = array(
                    'className' => $module . '\Modules',
                    'path' => APP_PATH . 'modules/' . $module . '/Module.php'
                );
            }
        }
        $modules_array = array(
            'loader' => array(
                'namespaces' => $namespaces,
            ),
            'modules' => $modules,
        );
        return $modules_array;
    }
}