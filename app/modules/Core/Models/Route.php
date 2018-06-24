<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Models;

use Engine\Mvc\Model;
use Phalcon\Di;

/**
 * Class Route
 * @package Module\Core\Models
 */
class Route extends Model
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var integer
     */
    protected $package_id;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $params;

    /**
     * @var integer
     */
    protected $ordering;

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
     * Method to set the value of field pattern
     *
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Method to set the value of field method
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Method to set the value of field package_id
     *
     * @param integer $package_id
     * @return $this
     */
    public function setPackageId($package_id)
    {
        $this->package_id = $package_id;

        return $this;
    }

    /**
     * Method to set the value of field controller
     *
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Method to set the value of field action
     *
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Method to set the value of field params
     *
     * @param string $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Returns the value of field method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the value of field package_id
     *
     * @return integer
     */
    public function getPackageId()
    {
        return $this->package_id;
    }

    /**
     * Returns the value of field controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Returns the value of field action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns the value of field params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
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
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     * @return $this
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', Alias::class, 'route_id', ['alias' => 'link', 'reusable' => true]);
        $this->hasOne('package_id', Package::class, 'id', ['alias' => 'package', 'reusable' => true]);
    }

    public function beforeValidationOnCreate()
    {
        $maxOrder = Route::maximum([
            "column" => "ordering",
        ]);
        $this->setOrdering($maxOrder + 1);
    }

    public static function getCacheActiveRoutes()
    {
        return md5("model_route.active_routes");
    }

    public function getMethodArray()
    {
        return json_decode($this->method, true);
    }

    public function getParamsArray()
    {
        return json_decode($this->params);
    }

    public static function getActiveRoutes()
    {
        return Di::getDefault()->getShared('modelsManager')->createBuilder()
            ->columns("route.*, package.*")
            ->addFrom(Route::class, "route")
            ->addFrom(Package::class, "package")
            ->where("package.id = route.package_id")
            ->andWhere("package.status = 1")
            ->andWhere("route.status = 1")
            ->getQuery()
            ->cache([
                'key' => self::getCacheActiveRoutes(),
                'lifetime' => 3600,
            ])
            ->execute();
    }
}
