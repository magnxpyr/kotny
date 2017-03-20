<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Package;

use Engine\Behavior\DiBehavior;
use Engine\Acl\Database;
use Engine\Meta;

/**
 * Class Manager
 * @package Engine\Package
 */
class Manager
{
    use Meta,
        DiBehavior;

    const
        /**
         * Module package.
         */
        PACKAGE_TYPE_MODULE = 'module',

        /**
         * Plugin package.
         */
        PACKAGE_TYPE_PLUGIN = 'plugin',

        /**
         * Theme package.
         */
        PACKAGE_TYPE_THEME = 'theme',

        /**
         * Widget package.
         */
        PACKAGE_TYPE_WIDGET = 'widget',

        /**
         * Library package.
         */
        PACKAGE_TYPE_LIBRARY = 'library';

    /**
     * @var
     */
    private $acl;

    /**
     * Install Module
     * @param $module
     */
    public function installModule($module)
    {
        $this->acl = new Database();

        $controllersPath = MODULES_PATH . "$module/Controllers";

        $roles = $this->acl->getRoles();
        // remove admin from roles since already has access on everything
        if(($key = array_search('admin', $roles)) !== false) {
            unset($roles[$key]);
        }

        $files = scandir($controllersPath);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $className = str_replace('.php', '', $file);

            $resourceName = str_replace('Controller', '', $className);
            $resourceName = 'module:core/' . $this->getDI()->get('helper')->uncamelize($resourceName);

            $class = "$module\\Controllers\\$className";
            $class = new $class();

            $actions = $this->getResourceAccess($class);
            $this->acl->addResource($resourceName, $actions);
        }

        $resources = require_once MODULES_PATH . "$module/Acl.php";

        if (isset($resources['allow'])) {
            $this->buildAcl($resources['allow'], $module, true);
        }
        if (isset($resources['deny'])) {
            $this->buildAcl($resources['deny'], $module, false);
        }
    }

    /**
     * Uninstall a module
     * @param $module
     */
    public function uninstallModule($module) {

    }

    /**
     * Get actions from a controller
     * @param string|\StdClass $class
     * @return array
     */
    private function getResourceAccess($class) {
        $functions = get_class_methods($class);
        $actions = ['*'];
        foreach ($functions as $function) {
            if (strpos($function, 'Action')) {
                $actions[] = str_replace('Action', '', $function);
            }
        }

        return $actions;
    }

    /**
     * Build Acl Object
     * @param $resources
     * @param $module
     * @param $allow
     */
    private function buildAcl($resources, $module, $allow) {
        $module = strtolower($module);
        foreach ($resources as $role => $resource) {
            foreach ($resource as $controller => $actions) {
                $this->acl->addResource("module:$module/$controller", $actions);
                foreach ($actions as $action) {
                    if ($allow) {
                        $this->acl->allow($role, "module:$module/$controller", $action);
                    } else {
                        $this->acl->deny($role, "module:$module/$controller", $action);
                    }
                }
            }
        }
    }
}