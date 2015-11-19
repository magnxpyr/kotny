<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Package;

use Engine\Behavior\DiBehavior;

/**
 * Class Manager
 * @package Engine\Package
 */
class Manager
{
    use DiBehavior;

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
     * Install Module
     */
    public function installModule()
    {
        $acl = $this->getDI()->get('acl');

        $controllersPath = ROOT_PATH . "modules/Core/Controllers";

        $files = scandir($controllersPath);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $className = str_replace('.php', '', $file);

            $resourceName = 'Code' . $this->getDI()->helper->uncamelize($className);

            $class = "Core\\$className";
            $class = new $class();
            $behaviors = $class->behaviors();
            if (!empty($behaviors)) {
                if (isset($behaviors['access']['only'])) {
                    $acl->addResource($resourceName, $behaviors['access']['only']);
                } else {
                    $actions = $this->getResourceAccess($class);
                    $acl->addResource($resourceName, $actions);
                }
                if (isset($behaviors['access']['rules'])) {
                    foreach ($behaviors['access']['roles'] as $rule) {
                        foreach ($rule['roles'] as $role) {
                            if ($rule['allow']) {
                                $acl->allow($role, $resourceName, $rule['actions']);
                            } else {
                                $acl->deny($role, $resourceName, $rule['actions']);
                            }
                        }
                    }
                }
            } else {
                $actions = $this->getResourceAccess($class);
                $acl->addResource($resourceName, $actions);
            }
        }
    }

    /**
     * Get actions from a controller
     * @param string|\StdClass $class
     * @return array
     */
    public function getResourceAccess($class) {
        $functions = get_class_methods($class);
        $actions = [];
        foreach ($functions as $function) {
            if (strpos($function, 'Action')) {
                $actions[] = str_replace('Action', '', $function);
            }
        }

        return $actions;
    }
}