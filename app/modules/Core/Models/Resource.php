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
 * Class Resource
 * @package Core\Models
 */
class Resource extends Model
{
    use EagerLoadingTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('resource');
        $this->hasMany('id', 'Core\Models\ResourceAccess', 'resource_id', ['alias' => 'resourceAccess', 'reusable' => true]);
        $this->hasMany('id', 'Core\Models\AccessList', 'resource_id', ['alias' => 'accessList', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'resource';
    }

}
