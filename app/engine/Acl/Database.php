<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Acl;

use Core\Models\AccessList;
use Core\Models\Resource;
use Core\Models\ResourceAccess;
use Core\Models\Role;
use Engine\Behavior\DiBehavior;
use Engine\Mvc\Exception;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl as PhalconAcl;
use Phalcon\Db;
use Phalcon\DI;
use Phalcon\Acl\Adapter;
use Phalcon\Acl\AdapterInterface;

/**
 * Engine\Acl\Database
 * Manages ACL lists in memory
 */
class Database extends Adapter implements AdapterInterface
{
    use DiBehavior;

    /**
     * Acl cache key.
     * @var string
     */
    protected $cache_key = "acl_data";

    /**
     * Acl adapter.
     * @var AclMemory
     */
    protected $acl;

    /**
     * Get acl system.
     *
     * @return AclMemory
     */
    public function getAcl()
    {
        if (!$this->acl) {
            $cache = $this->getDI()->get('cache');
            $acl = $cache->get($this->cache_key);
            if ($acl === null) {
                $acl = new AclMemory();
                $acl->setDefaultAction(PhalconAcl::DENY);
                // Prepare Roles.
                $roles = Role::find();
                foreach ($roles as $role) {
                    $acl->addRole($role->getId());
                }
                // Looking for all controllers inside modules and get actions.
                $resources = Resource::with('resourceAccess');
                foreach ($resources as $resource) {
                    if ($resource->getName() == '*') continue;

                    $actions = [];
                    foreach ($resource->resourceAccess as $action) {
                        $actions[] = $action->getAccessName();
                    }
                    $acl->addResource($resource->getName(), $actions);
                }

                // Load from database.
                $accessList = AccessList::with('resource', 'role');
                foreach ($accessList as $access) {
                    if ($access->getStatus() == 1) {
                        $acl->allow($access->role->getId(), $access->resource->getName(), $access->getAccessName());
                    } else {
                        $acl->deny($access->role->getId(), $access->resource->getName(), $access->getAccessName());
                    }

                }
                $cache->save($this->cache_key, $acl, DEV ? 0 : 2592000); // 30 days cache.
            }
            $this->acl = $acl;
        }
        return $this->acl;
    }


    /**
     * Get acl object.
     *
     * @param string $objectName Object name.
     *
     * @return null|\stdClass
     */
    public function getObject($objectName)
    {
        $object = new \stdClass();
        $objectClass = new $objectName;
        if (function_exists("behaviors")) {
            $behaviors = $objectClass->behaviors;
            if (isset($behaviors['actions'])) {
                foreach ($behaviors['actions'] as $action) {
                    $object->actions[] = $action;
                }
            }
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>$acl->addRole(new Phalcon\Acl\Role('administrator'), 'consultor');</code>
     * <code>$acl->addRole('administrator', 'consultor');</code>
     *
     * @param  \Phalcon\Acl\Role|string $role
     * @param  string                   $accessInherits
     * @return boolean
     */
    public function addRole($role, $accessInherits = 0)
    {
        if (!is_object($role)) {
            $role = new AclRole($role);
        }

        $roleModel = Role::findFirstByName($role->getName());

        if (!$roleModel) {
            $roleModel
                ->setParentId($accessInherits)
                ->setName($role->getName())
                ->setDescription($role->getDescription())
                ->create();

            $this->db->execute(
                'INSERT INTO access_list (role_id, resource_id, access_name, status) VALUES (?, ?, ?, ?)',
                [$role->getName(), '*', '*', $this->_defaultAccess]
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string                 $roleName
     * @param  string                 $roleToInherit
     * @throws \Phalcon\Acl\Exception
     */
    public function addInherit($roleName, $roleToInherit)
    {
        $role = Role::findFirstByName($roleName);
        if (!$role) {
            throw new Exception("Role '" . $roleName . "' does not exist in the role list");
        }

        $roleInherit = Role::findFirstByName($roleToInherit);
        if (!$role) {
            throw new Exception("Inherit Role '" . $roleName . "' does not exist in the role list");
        }

        $role->setParentId($roleInherit->getId());
        $role->save();
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $roleName
     * @return boolean
     */
    public function isRole($roleName)
    {
        $role = Role::findFirstByName($roleName);

        return (bool) $role;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $resourceName
     * @return boolean|int
     */
    public function isResource($resourceName)
    {
        $resource = Resource::findFirstByName($resourceName);

        return $resource ? $resource->getId() : false;
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>
     * //Add a resource to the the list allowing access to an action
     * $acl->addResource(new Phalcon\Acl\Resource('customers'), 'search');
     * $acl->addResource('customers', 'search');
     * //Add a resource  with an access list
     * $acl->addResource(new Phalcon\Acl\Resource('customers'), array('create', 'search'));
     * $acl->addResource('customers', array('create', 'search'));
     * </code>
     *
     * @param  \Phalcon\Acl\Resource|string $resource
     * @param  array|string                 $accessList
     * @return boolean
     */
    public function addResource($resource, $accessList = null)
    {
        if (is_object($resource)) {
            $resource = $resource->getName();
        }

        $resourceModel = Resource::findFirstByName($resource);

        if (!$resourceModel) {
            $resourceModel = new Resource();
            $resourceModel->setName($resource)->create();
        }

        if ($accessList) {
            return $this->addResourceAccess($resource, $accessList);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string                 $resourceName
     * @param  array|string           $accessList
     * @return boolean
     * @throws \Phalcon\Acl\Exception
     */
    public function addResourceAccess($resourceName, $accessList)
    {
        $resourceId = $this->isResource($resourceName);
        if (!$resourceId) {
            throw new Exception("Resource '" . $resourceName . "' does not exist in ACL");
        }

        if (!is_array($accessList)) {
            $accessList = [$accessList];
        }

        foreach ($accessList as $accessName) {
            $resourceAccess = ResourceAccess::findFirst([
                'conditions' => 'resource_id = ?1 AND access_name = ?2',
                'bind' => [1 => $resourceId, 2 => $accessName]
            ]);
            if (!$resourceAccess) {
                $resourceAccess = new ResourceAccess();
                $resourceAccess
                        ->setResourceId($resourceId)
                        ->setAccessName($accessName)
                        ->create();
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Phalcon\Acl\Resource[]
     */
    public function getResources()
    {
        $resources = [];
        $resource = Resource::find();

        foreach ($resource as $row) {
            $resources[] = $row->getName();
        }

        return $resources;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Phalcon\Acl\Role[]
     */
    public function getRoles()
    {
        $roles = [];
        $role = Role::find();

        foreach ($role as $row) {
            $roles[] = $row->getName();
        }

        return $roles;
    }

    /**
     * {@inheritdoc}
     *
     * @param string       $resourceName
     * @param array|string $accessList
     */
    public function dropResourceAccess($resourceName, $accessList)
    {
    }

    /**
     * {@inheritdoc}
     * You can use '*' as wildcard
     * Example:
     * <code>
     * //Allow access to guests to search on customers
     * $acl->allow('guests', 'customers', 'search');
     * //Allow access to guests to search or create on customers
     * $acl->allow('guests', 'customers', array('search', 'create'));
     * //Allow access to any role to browse on products
     * $acl->allow('*', 'products', 'browse');
     * //Allow access to any role to browse on any resource
     * $acl->allow('*', '*', 'browse');
     * </code>
     *
     * @param string       $roleName
     * @param string       $resourceName
     * @param array|string $access
     */
    public function allow($roleName, $resourceName, $access)
    {
        $this->allowOrDeny($roleName, $resourceName, $access, Acl::ALLOW);
    }

    /**
     * {@inheritdoc}
     * You can use '*' as wildcard
     * Example:
     * <code>
     * //Deny access to guests to search on customers
     * $acl->deny('guests', 'customers', 'search');
     * //Deny access to guests to search or create on customers
     * $acl->deny('guests', 'customers', array('search', 'create'));
     * //Deny access to any role to browse on products
     * $acl->deny('*', 'products', 'browse');
     * //Deny access to any role to browse on any resource
     * $acl->deny('*', '*', 'browse');
     * </code>
     *
     * @param  string       $roleName
     * @param  string       $resourceName
     * @param  array|string $access
     * @return boolean
     */
    public function deny($roleName, $resourceName, $access)
    {
        $this->allowOrDeny($roleName, $resourceName, $access, Acl::DENY);
    }

    /**
     * {@inheritdoc}
     * Example:
     * <code>
     * //Does Andres have access to the customers resource to create?
     * $acl->isAllowed('Andres', 'Products', 'create');
     * //Do guests have access to any resource to edit?
     * $acl->isAllowed('guests', '*', 'edit');
     * </code>
     *
     * @param string $role
     * @param string $resource
     * @param string $access
     *
     * @return bool
     */
    public function isAllowed($role, $resource, $access)
    {

    }

    /**
     * Inserts/Updates a permission in the access list
     *
     * @param  string                 $roleName
     * @param  string                 $resourceName
     * @param  string                 $accessName
     * @param  integer                $action
     * @return boolean
     * @throws \Engine\Mvc\Exception
     */
    protected function insertOrUpdateAccess($roleName, $resourceName, $accessName, $action)
    {
        $roleId = Role::findFirstByName($roleName)->getId();
        $resourceId = Resource::findFirstByName($resourceName)->getId();

        /**
         * Check if the access is valid in the resource
         */
        $resourceAccess = ResourceAccess::findFirst([
            'conditions' => 'resource_id = ?1 AND access_name = ?2',
            'bind' => [1 => $resourceId, 2 => $accessName]
        ]);
        if (!$resourceAccess) {
            throw new Exception(
                "Access '" . $accessName . "' does not exist in resource '" . $resourceName . "' in ACL"
            );
        }

        /**
         * Update the access in access_list
         */
        $accessList = AccessList::findFirst([
            'conditions' => 'role_id = ?1 AND resource_id = ?2 AND access_name = ?3',
            'bind' => [1 => $roleId, 2 => $resourceId, 3 => $accessName]
        ]);

        if (!$accessList) {
            $accessList = new AccessList();
        }
        $accessList
                ->setRoleId($roleId)
                ->setResourceId($resourceId)
                ->setAccessName($accessName)
                ->setStatus($action)
                ->save();

        return true;
    }

    /**
     * Inserts/Updates a permission in the access list
     *
     * @param  string                 $roleName
     * @param  string                 $resourceName
     * @param  array|string           $access
     * @param  integer                $action
     * @throws \Engine\Mvc\Exception
     */
    protected function allowOrDeny($roleName, $resourceName, $access, $action)
    {
        if (!$this->isRole($roleName)) {
            throw new Exception('Role "' . $roleName . '" does not exist in the list');
        }

        if (!is_array($access)) {
            $access = [$access];
        }

        foreach ($access as $accessName) {
            $this->insertOrUpdateAccess($roleName, $resourceName, $accessName, $action);
        }
    }

    public function checkViewLevel($viewLevel)
    {
        $allow = false;
        $roles = json_decode($viewLevel);
        if (in_array($this->getDI()->get('auth')->getUserRole(), $roles))
            $allow = true;

        return $allow;
    }
}