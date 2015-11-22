<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;
use Sb\Framework\Mvc\Model\EagerLoadingTrait;

/**
 * Class AccessList
 * @package Core\Models
 */
class AccessList extends Model
{
    use EagerLoadingTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $role_id;

    /**
     * @var integer
     */
    protected $resource_id;

    /**
     * @var string
     */
    protected $access_name;

    /**
     * @var integer
     */
    protected $status;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field role_id
     *
     * @param integer $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Method to set the value of field resource_id
     *
     * @param integer $resource_id
     * @return $this
     */
    public function setResourceId($resource_id)
    {
        $this->resource_id = $resource_id;

        return $this;
    }

    /**
     * Method to set the value of field access_name
     *
     * @param string $access_name
     * @return $this
     */
    public function setAccessName($access_name)
    {
        $this->access_name = $access_name;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Returns the value of field resource_id
     *
     * @return integer
     */
    public function getResourceId()
    {
        return $this->resource_id;
    }

    /**
     * Returns the value of field access_name
     *
     * @return string
     */
    public function getAccessName()
    {
        return $this->access_name;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('access_list');
        $this->belongsTo('resource_id', 'Core\Models\Resource', 'id', ['alias' => 'resource', 'reusable' => true]);
        $this->belongsTo('role_id', 'Core\Models\Role', 'id', ['alias' => 'role', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'access_list';
    }

}
