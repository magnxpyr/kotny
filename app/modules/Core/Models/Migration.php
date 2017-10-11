<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Models;

use Engine\Mvc\Model;
use Engine\Package\PackageType;

/**
 * Class Migration
 * @package Module\Core\Models
 */
class Migration extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $name;

    /**
     * @var PackageType
     */
    private $package_type;
    /**
     * @var integer
     */
    private $package_id;

    /**
     * @var integer
     */
    private $start_time;

    /**
     * @var integer
     */
    private $end_time;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param int $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return int
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param int $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return PackageType
     */
    public function getPackageType()
    {
        return $this->package_type;
    }

    /**
     * @param PackageType $package_type
     */
    public function setPackageType($package_type)
    {
        $this->package_type = $package_type;
    }

    /**
     * @return int
     */
    public function getPackageId()
    {
        return $this->package_id;
    }

    /**
     * @param int $package_id
     */
    public function setPackageId($package_id)
    {
        $this->package_id = $package_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('migration');
        $this->hasOne('package_id', Package::class, 'id', ['alias' => 'package', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'migration';
    }
}