<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\EagerLoadingTrait;

/**
 * Class ResourceAccess
 * @package Core\Models
 */
class ResourceAccess extends Model
{
    use EagerLoadingTrait;

    /**
     * @var integer
     */
    protected $resource_id;

    /**
     * @var string
     */
    protected $access_name;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('resource_access');
        $this->belongsTo('resource_id', 'Core\Models\Resource', 'id', ['alias' => 'resource', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'resource_access';
    }

}
