<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Acl;

use Module\Core\Models\AccessList;
use Module\Core\Models\Resource;
use Module\Core\Models\ResourceAccess;
use Module\Core\Models\Role;
use Engine\Behavior\AclBehavior;
use Engine\Mvc\Exception;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl as PhalconAcl;
use Phalcon\Acl\Adapter;
use Phalcon\Acl\AdapterInterface;

/**
 * Manages ACL lists in memory
 * Class Database
 * @package Engine\Acl
 */
class Database extends Adapter implements AdapterInterface
{
    use AclBehavior;

    /**
     * Sets the default access level (Phalcon\Acl::ALLOW or Phalcon\Acl::DENY) for no arguments provided in isAllowed action if there exists func for accessKey
     *
     * @param int $defaultAccess
     */
    public function setNoArgumentsDefaultAction($defaultAccess)
    {

    }

    /**
     * Returns the default ACL access level for no arguments provided in isAllowed action if there exists func for accessKey
     *
     * @return int
     */
    public function getNoArgumentsDefaultAction()
    {

    }

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
                $acl = $cache->get($this->cacheKey);
            } else {
                $acl = new MemoryBase();
                $acl->setDefaultAction(PhalconAcl::DENY);
                // Prepare Roles.
                $roles = Role::find();
                foreach ($roles as $role) {
                    if ($role->getParentId() > 0) {
                        $acl->addRole($role->getId(), $role->getParentId());
                    } else {
                        $acl->addRole($role->getId());
                    }
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
                $cache->save($this->cacheKey, $acl, $this->cacheExpire);
            }
            $this->acl = $acl;
        }
        return $this->acl;
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
        if ($roleName == '*') {
            return true;
        }

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
            $roles[$row->getId()] = $row->getName();
        }

        return $roles;
    }

    /**
     * {@inheritdoc}
     *
     * @param string       $resourceName
     * @param array|string $accessList
     */
    public function dropResourceAccess($resourceName, $accessList = null)
    {
        $resource = Resource::findFirstByName($resourceName);
        AccessList::findByResourceId($resource->getId())->delete();
        ResourceAccess::findByResourceId($resource->getId())->delete();
        $resource->delete();
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
    public function allow($roleName, $resourceName, $access, $func = null)
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
    public function deny($roleName, $resourceName, $access, $func = null)
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
     * @param string $roleName
     * @param string $resourceName
     * @param string $accessName
     *
     * @return bool
     */
    public function isAllowed($roleName, $resourceName, $accessName, array $parameters = null)
    {
        if ($roleName == '*') {
            $roleId = $roleName;
        } else {
            $roleId = Role::findFirstByName($roleName)->getId();
        }
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

        $accessList = AccessList::findFirst([
            'conditions' => 'role_id = ?1 AND resource_id = ?2 AND access_name = ?3',
            'bind' => [1 => $roleId, 2 => $resourceId, 3 => $accessName]
        ]);

        if (!$accessList) {
            return false;
        }
        return true;
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
        if ($roleName == '*') {
            $roleId = $roleName;
        } else {
            $roleId = Role::findFirstByName($roleName)->getId();
        }
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
}