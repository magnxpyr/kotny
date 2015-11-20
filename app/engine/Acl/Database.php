<?php

namespace Engine\Acl;

use Core\Models\AccessList;
use Core\Models\Resource;
use Core\Models\Role;
use Engine\Mvc\Exception;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Resource as AclResource;
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
                $roleNames = [];
                foreach ($roles as $role) {
                    $roleNames[$role->id] = $role->name;
                    $acl->addRole($role->name);
                }
                // Looking for all controllers inside modules and get actions.
                $resources = $this->addResources($acl);
                // Load from database.
                $access = AccessList::find();
                foreach ($access as $item) {
                    $value = $item->status;
                    if (
                        array_key_exists($item->resource, $resources) &&
                        in_array($item->action, $resources[$item->resource]['actions']) &&
                        ($value == 1 || $value == 2)
                    ) {
                        $acl->$value($roleNames[$item->role_id], $item->resource, $item->action);
                    }
                }
                $cache->save($this->cache_key, $acl, 2592000); // 30 days cache.
            }
            $this->acl = $acl;
        }
        return $this->acl;
    }

    /**
     * Add resources to acl.
     *
     * @param AclMemory $acl     Acl object.
     * @param array     $objects Related objects collection.
     * @return array
     */
    private function addResources($acl)
    {
        $registry = $this->getDI()->get('registry');
        $resources = [];
        foreach ($registry->modules as $module) {
            $controllersPath = ROOT_PATH . "modules\\$module\\Controllers\\";
            $files = scandir($controllersPath);
            foreach ($files as $file) {
                if ($file == "." || $file == "..") {
                    continue;
                }
                $class = str_replace('.php', '', $file);
                $object = $this->getObject($class);
                if ($object == null) {
                    continue;
                }
                $objects[$class]['actions'] = $object->actions;
                $objects[$class]['options'] = $object->options;
            }
            // Add objects to resources.
            foreach ($objects as $key => $object) {
                if (empty($object['actions'])) {
                    $object['actions'] = [];
                }
                $acl->addResource($key, $object['actions']);
            }

        }
        return $resources;
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

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM role WHERE name = ?', null, [$role->getName()]
        );

        if (!$exists[0]) {
            $this->options['db']->execute(
                'INSERT INTO role (parent_id, name, description) VALUES (?, ?, ?)',
                [$accessInherits, $role->getName(), $role->getDescription()]
            );

            $this->options['db']->execute(
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
        $sql = 'SELECT COUNT(*) FROM role WHERE name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, array($roleName));
        if (!$exists[0]) {
            throw new Exception("Role '" . $roleName . "' does not exist in the role list");
        }

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->options['rolesInherits'] . ' WHERE roles_name = ? AND roles_inherit = ?',
            null,
            array($roleName, $roleToInherit)
        );

        if (!$exists[0]) {
            $this->options['db']->execute(
                'INSERT INTO ' . $this->options['rolesInherits'] . ' VALUES (?, ?)',
                array($roleName, $roleToInherit)
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $roleName
     * @return boolean
     */
    public function isRole($roleName)
    {
        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM role WHERE name = ?', null, [$roleName]
        );

        return (bool) $exists[0];
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
        if (!is_object($resource)) {
            $resource = new Resource($resource);
        }

        $exists = $this->options['db']->fetchOne(
            'SELECT COUNT(*) FROM resource WHERE name = ?', null, [$resource->getName()]
        );

        if (!$exists[0]) {
            $this->options['db']->execute(
                'INSERT INTO resource (name) VALUES (?)', [$resource->getName()]
            );
        }

        if ($accessList) {
            return $this->addResourceAccess($resource->getName(), $accessList);
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

        $sql = 'SELECT COUNT(*) FROM resource_access WHERE resources_id = ? AND access_name = ?';

        if (!is_array($accessList)) {
            $accessList = [$accessList];
        }

        foreach ($accessList as $accessName) {
            $exists = $this->options['db']->fetchOne($sql, null, [$resourceId, $accessName]);
            if (!$exists[0]) {
                $this->options['db']->execute(
                    'INSERT INTO resource_access VALUES (?, ?)',
                    [$resourceId, $accessName]
                );
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
            $resources[] = new AclResource($row->getName());
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
            $roles[] = new AclRole($row->getId());
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
        $sql = implode(' ', array(
            'SELECT allowed FROM', $this->options['accessList'], 'AS a',
            // role_name in:
            'WHERE roles_name IN (',
            // given 'role'-parameter
            'SELECT ? ',
            // inherited role_names
            'UNION SELECT roles_inherit FROM', $this->options['rolesInherits'], 'WHERE roles_name = ?',
            // or 'any'
            "UNION SELECT '*'",
            ')',
            // resources_name should be given one or 'any'
            "AND resources_name IN (?, '*')",
            // access_name should be given one or 'any'
            "AND access_name IN (?, '*')",
            // order be the sum of bools for 'literals' before 'any'
            "ORDER BY (roles_name != '*')+(resources_name != '*')+(access_name != '*') DESC",
            // get only one...
            'LIMIT 1'
        ));

        // fetch one entry...
        $allowed = $this->options['db']->fetchOne($sql, Db::FETCH_NUM, array($role, $role, $resource, $access));
        if (is_array($allowed)) {
            return (bool) $allowed[0];
        }

        /**
         * Return the default access action
         */

        return $this->_defaultAccess;
    }

    /**
     * Inserts/Updates a permission in the access list
     *
     * @param  string                 $roleName
     * @param  string                 $resourceName
     * @param  string                 $accessName
     * @param  integer                $action
     * @return boolean
     * @throws \Phalcon\Acl\Exception
     */
    protected function insertOrUpdateAccess($roleName, $resourceName, $accessName, $action)
    {
        /**
         * Check if the access is valid in the resource
         */
        $sql = 'SELECT COUNT(*) FROM resource_access ra, resource r WHERE
              r.name = ? AND ra.access_name = ? AND ra.resource_id = r.id';

        $exists = $this->options['db']->fetchOne($sql, null, array($resourceName, $accessName));
        if (!$exists[0]) {
            throw new Exception(
                "Access '" . $accessName . "' does not exist in resource '" . $resourceName . "' in ACL"
            );
        }

        /**
         * Update the access in access_list
         */
        $sql = 'SELECT COUNT(*) FROM access_list al, role, resource r WHERE r.name = ? AND r.name = ? AND al.access_name = ?
            AND r.name';
        $exists = $this->options['db']->fetchOne($sql, null, array($roleName, $resourceName, $accessName));
        if (!$exists[0]) {
            $sql = 'INSERT INTO ' . $this->options['accessList'] . ' VALUES (?, ?, ?, ?)';
            $params = array($roleName, $resourceName, $accessName, $action);
        } else {
            $sql = 'UPDATE ' .
                $this->options['accessList'] .
                ' SET allowed = ? WHERE roles_name = ? AND resources_name = ? AND access_name = ?';
            $params = array($action, $roleName, $resourceName, $accessName);
        }

        $this->options['db']->execute($sql, $params);

        /**
         * Update the access '*' in access_list
         */
        $sql = 'SELECT COUNT(*) FROM ' .
            $this->options['accessList'] .
            ' WHERE roles_name = ? AND resources_name = ? AND access_name = ?';
        $exists = $this->options['db']->fetchOne($sql, null, array($roleName, $resourceName, '*'));
        if (!$exists[0]) {
            $sql = 'INSERT INTO ' . $this->options['accessList'] . ' VALUES (?, ?, ?, ?)';
            $this->options['db']->execute($sql, array($roleName, $resourceName, '*', $this->_defaultAccess));
        }

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