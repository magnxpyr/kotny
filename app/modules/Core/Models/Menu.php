<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Models;

use Engine\Mvc\Model;
use Phalcon\Mvc\Model\EagerLoadingTrait;

/**
 * Class Menu
 * @package Module\Core\Models
 */
class Menu extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $menu_type_id;

    /**
     * @var string
     */
    private $prepend;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $path;
    
    /**
     * @var integer
     */
    private $status;

    /**
     * @var integer
     */
    private $show_title;

    /**
     * @var integer
     */
    private $parent_id;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var integer
     */
    private $lft;

    /**
     * @var integer
     */
    private $rgt;

    /**
     * @var integer
     */
    private $view_level;

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
     * Method to set the value of field menu_type_id
     *
     * @param integer $menu_type_id
     * @return $this
     */
    public function setMenuTypeId($menu_type_id)
    {
        $this->menu_type_id = $menu_type_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrepend()
    {
        return $this->prepend;
    }

    /**
     * Prepend an html object before the title
     * 
     * @param string $prepend
     */
    public function setPrepend($prepend)
    {
        $this->prepend = $prepend;
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
     * Method to set the value of field path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

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
     * @param int $show_title
     */
    public function setShowTitle($show_title)
    {
        $this->show_title = $show_title;
    }

    /**
     * Method to set the value of field parent_id
     *
     * @param integer $parent_id
     * @return $this
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * Method to set the value of field level
     *
     * @param integer $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Method to set the value of field lft
     *
     * @param integer $lft
     * @return $this
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Method to set the value of field rgt
     *
     * @param integer $rgt
     * @return $this
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field menu_type_id
     *
     * @return integer
     */
    public function getMenuTypeId()
    {
        return $this->menu_type_id;
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
     * Returns the value of field path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
    public function getShowTitle()
    {
        return $this->show_title;
    }

    /**
     * Returns the value of field parent_id
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Returns the value of field level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Returns the value of field lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Returns the value of field rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('menu_type_id', 'Module\Core\Models\MenuType', 'id', ['alias' => 'menuType', 'reusable' => true]);
        $this->hasOne('view_level', 'Module\Core\Models\ViewLevel', 'id', ['alias' => 'viewLevel', 'reusable' => true]);
    }

    /**
     * Set default values before create
     */
    public function beforeValidation()
    {
        if(empty($this->id)) {
            $this
                ->setLevel(1)
                ->setLft(0)
                ->setRgt(0);
        }
    }
}
