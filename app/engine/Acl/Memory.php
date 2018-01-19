<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Acl;

use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;

class Memory extends AclMemory
{
    use AclTrait;

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
            /** @var \Phalcon\Cache\Backend $cache */
            $cache = $this->getDI()->get('cache');
            if ($cache->exists($this->getCacheKey(), $this->getCacheExpire())) {
                $this->acl = $cache->get($this->getCacheKey());
            } else {
                $this->acl = new BaseMemory();
                $this->acl->setDefaultAction(Acl::DENY);

                $roles = require_once APP_PATH . 'config/roles.php';
                foreach ($roles as $key => $role) {
                    if (is_array($role)) {
                        $this->acl->addRole(new Acl\Role($role[0], $role[0]), $role[1]);
                    } else {
                        $this->acl->addRole(new Acl\Role($role, $role));
                    }
                }

                $modules = $this->getDI()->get("registry")->modules;
                foreach ($modules as $module) {
                    $module = $module->name;
                    $resources = require_once MODULES_PATH . $module . "/Acl.php";
                    if (isset($resources['allow'])) {
                        $this->buildAcl($resources['allow'], $module, true);
                    }
                    if (isset($resources['deny'])) {
                        $this->buildAcl($resources['deny'], $module, false);
                    }
                }
                $this->acl->allow('admin', '*', '*');
                $cache->save($this->getCacheKey(), $this->acl, $this->getCacheExpire());
            }
            $this->acl->adapter = new Database();
        }
        return $this->acl;
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
                $this->acl->addResource(new Acl\Resource("module:$module/$controller"), $actions);
                if ($allow) {
                    $this->allowResources($actions, $role, $module, $controller);
                } else {
                    $this->denyResources($actions, $role, $module, $controller);
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
     */
    private function allowResources($actions, $role, $module, $controller) {
        foreach ($actions as $action) {
            $this->acl->allow($role, "module:$module/$controller", $action);
        }
    }

    /**
     * Deny resources access
     * @param $actions
     * @param $role
     * @param $module
     * @param $controller
     */
    private function denyResources($actions, $role, $module, $controller) {
        foreach ($actions as $action) {
            $this->acl->deny($role, "module:$module/$controller", $action);
        }
    }
}