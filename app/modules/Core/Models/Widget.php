<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Models;

use Engine\Mvc\Model;

/**
 * Class Widget
 * @package Module\Core\Models
 */
class Widget extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $package_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $ordering;

    /**
     * @var string
     */
    private $position;

    /**
     * @var integer
     */
    private $publish_up;

    /**
     * @var integer
     */
    private $publish_down;

    /**
     * @var integer
     */
    private $view_level;

    /**
     * @var string
     */
    private $params;

    /**
     * @var integer
     */
    private $show_title;

    /**
     * @var integer
     */
    private $status;

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
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field ordering
     *
     * @param integer $ordering
     * @return $this
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Method to set the value of field position
     *
     * @param string $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Method to set the value of field publish_up
     *
     * @param integer $publish_up
     * @return $this
     */
    public function setPublishUp($publish_up)
    {
        $this->publish_up = $publish_up;

        return $this;
    }

    /**
     * Method to set the value of field publish_down
     *
     * @param integer $publish_down
     * @return $this
     */
    public function setPublishDown($publish_down)
    {
        $this->publish_down = $publish_down;

        return $this;
    }

    /**
     * Method to set the value of field view_level
     *
     * @param integer $view_level
     * @return $this
     */
    public function setViewLevel($view_level)
    {
        $this->view_level = $view_level;

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
     * Method to set the value of field show_title
     *
     * @param integer $show_title
     * @return $this
     */
    public function setShowTitle($show_title)
    {
        $this->show_title = $show_title;

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
     * Returns the value of field package_id
     *
     * @return integer
     */
    public function getPackageId()
    {
        return $this->package_id;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field ordering
     *
     * @return integer
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Returns the value of field position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Returns the value of field publish_up
     *
     * @return integer
     */
    public function getPublishUp()
    {
        return $this->publish_up;
    }

    /**
     * Returns the value of field publish_down
     *
     * @return integer
     */
    public function getPublishDown()
    {
        return $this->publish_down;
    }

    /**
     * Returns the value of field view_level
     *
     * @return integer
     */
    public function getViewLevel()
    {
        return $this->view_level;
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
     * Returns the value of field show_title
     *
     * @return integer
     */
    public function getShowTitle()
    {
        return $this->show_title;
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
        $this->hasOne('view_level', ViewLevel::class, 'id', ['alias' => 'viewLevel', 'reusable' => true]);
        $this->belongsTo('package_id', Package::class, 'id', ['alias' => 'package', 'reusable' => true]);
    }

    public function beforeValidation()
    {
        $this->setPublishUp($this->getDI()->getShared('helper')->timestampFromDate($this->getPublishUp()));
        if (!empty($this->getPublishDown())) {
            $this->setPublishDown($this->getDI()->getShared('helper')->timestampFromDate($this->getPublishDown()));
        } else {
            $this->setPublishDown(null);
        }
    }
}