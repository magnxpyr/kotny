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
 * Class Menu
 * @package Core\Models
 */
class Menu extends Model
{
    use EagerLoadingTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $menu_type_id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var integer
     */
    protected $status;

    /**
     * @var integer
     */
    protected $parent_id;

    /**
     * @var integer
     */
    protected $level;

    /**
     * @var integer
     */
    protected $lft;

    /**
     * @var integer
     */
    protected $rgt;

    /**
     * @var integer
     */
    protected $view_level;

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
     * Method to set the value of field type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Method to set the value of field link
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

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
     * Returns the value of field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Returns the value of field link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
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
        $this->setSource('menu');
        $this->belongsTo('menu_type_id', 'Core\Models\MenuType', 'id', ['alias' => 'menuType', 'reusable' => true]);
        $this->belongsTo('view_level', 'Core\Models\ViewLevel', 'id', ['alias' => 'viewLevel', 'reusable' => true]);
    //    $this->addBehavior(new Model\Behavior\NestedSet(['rootAttribute' => 'parent_id']));
    }

    public function getSource()
    {
        return 'menu';
    }

    /**
     * Set default values before create
     */
    public function beforeValidation()
    {
        if(empty($this->getId())) {
            $this
                ->setLevel(1)
                ->setLft(0)
                ->setRgt(0);
        }
    }
}
