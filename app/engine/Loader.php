<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
class Loader extends \Phalcon\Loader
{
    public function init() {
        // Phalcon loader
        $this->registerNamespaces([
            'Phalcon' => APP_PATH . 'vendor/phalcon/incubator/Library/Phalcon/',
            'Engine' => APP_PATH . 'engine/',
            'Widget' => APP_PATH . 'widgets/',
            'Module' => APP_PATH . 'modules/'
        ]);
        $this->register();

        // Composer loader
        require_once APP_PATH . 'vendor/autoload.php';
    }

    public function modulesConfig($modules_list) {
        $modules = [];
        $routes = [];
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $module = $module->name;
                $routePath = APP_PATH . "modules/$module/Routes.php";
                if(file_exists($routePath)) {
                    $routes[] = "Module\\$module\\Routes";
                }

                $modules[Text::uncamelize($module, "-")] = array(
                    'className' => "Module\\$module\\Module",
                    'path' => APP_PATH . "modules/$module/Module.php"
                );
            }
        }

        $modules_array = array(
            'modules' => $modules,
            'routes' => $routes
        );

        return $modules_array;
    }
}