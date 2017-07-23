<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Acl;

use Engine\Behavior\AclBehavior;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;

class Memory
{
    use AclBehavior;

    /**
     * Get acl system.
     *
     * @return AclMemory
     */
    public function getAcl()
    {
        if (DEV) {
            $this->cacheExpire = 1;
        }
        if (!$this->acl) {
            $cache = $this->getDI()->get('cache');
            if ($cache->exists($this->cacheKey, $this->cacheExpire)) {
                $this->acl = $cache->get($this->cacheKey);
            } else {
                $this->acl = new MemoryBase();
                $this->acl->setDefaultAction(Acl::DENY);

                $roles = require_once APP_PATH . 'config/roles.php';
                $roleIds = [];
                foreach ($roles as $key => $role) {
                    if (is_array($role)) {
                        $roleIds[$role[0]] = $key + 1;
                        $this->acl->addRole(new Acl\Role((string)($key + 1), $role[0]), (string)$roleIds[$role[1]]);
                    } else {
                        $roleIds[$role] = $key + 1;
                        $this->acl->addRole(new Acl\Role((string)($key + 1), $role));
                    }
                }

                $modules = $this->getDI()->get("registry")->modules;
                foreach ($modules as $module) {
                    $module = $module->name;
                    $resources = require_once MODULES_PATH . $module . "/Acl.php";
                    if (isset($resources['allow'])) {
                        $this->buildAcl($resources['allow'], $module, $roleIds, true);
                    }
                    if (isset($resources['deny'])) {
                        $this->buildAcl($resources['deny'], $module, $roleIds, false);
                    }
                }
                $this->acl->allow((string)$roleIds['admin'], '*', '*');
                $cache->save($this->cacheKey, $this->acl, $this->cacheExpire);
            }
        }
        return $this->acl;
    }

    /**
     * Build Acl Object
     * @param $resources
     * @param $module
     * @param $roleIds
     * @param $allow
     */
    private function buildAcl($resources, $module, $roleIds, $allow) {
        $module = strtolower($module);
        foreach ($resources as $role => $resource) {
            foreach ($resource as $controller => $actions) {
                $this->acl->addResource(new Acl\Resource("module:$module/$controller"), $actions);
                if ($allow) {
                    $this->allowResources($actions, $role, $module, $controller, $roleIds);
                } else {
                    $this->denyResources($actions, $role, $module, $controller, $roleIds);
                }

            }
        }
    }

    /**
     * Allow resources access
     * @param $actions
     * @param $role
     * @param $module
     * @param $controller
     * @param $roleIds
     */
    private function allowResources($actions, $role, $module, $controller, $roleIds) {
        foreach ($actions as $action) {
            if ($role == '*') {
                $this->acl->allow($role, "module:$module/$controller", $action);
            } else {
                $this->acl->allow($roleIds[$role], "module:$module/$controller", $action);
            }
        }
    }

    /**
     * Deny resources access
     * @param $actions
     * @param $role
     * @param $module
     * @param $controller
     * @param $roleIds
     */
    private function denyResources($actions, $role, $module, $controller, $roleIds) {
        foreach ($actions as $action) {
            if ($role == '*') {
                $this->acl->deny($role, "module:$module/$controller", $action);
            } else {
                $this->acl->deny($roleIds[$role], "module:$module/$controller", $action);
            }
        }
    }
}