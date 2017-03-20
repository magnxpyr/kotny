<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Engine\Mvc\Model;

/**
 * Class Content
 * @package Core\Models
 */
class Content extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $introtext;

    /**
     * @var string
     */
    private $fulltext;

    /**
     * @var string
     */
    private $metadata;

    /**
     * @var integer
     */
    private $category;

    /**
     * @var integer
     */
    private $hits;

    /**
     * @var integer
     */
    private $featured;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var integer
     */
    private $view_level;

    /**
     * @var integer
     */
    private $created_at;

    /**
     * @var integer
     */
    private $created_by;

    /**
     * @var integer
     */
    private $modified_at;

    /**
     * @var integer
     */
    private $modified_by;

    /**
     * @var integer
     */
    private $published_at;

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
     * Method to set the value of field alias
     *
     * @param string $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Method to set the value of field introtext
     *
     * @param string $introtext
     * @return $this
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;

        return $this;
    }

    /**
     * Method to set the value of field fulltext
     *
     * @param string $fulltext
     * @return $this
     */
    public function setFulltext($fulltext)
    {
        $this->fulltext = $fulltext;

        return $this;
    }

    /**
     * Method to set the value of field metadata
     *
     * @param string $metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Method to set the value of field category
     *
     * @param integer $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Method to set the value of field hits
     *
     * @param integer $hits
     * @return $this
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Method to set the value of field featured
     *
     * @param integer $featured
     * @return $this
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

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
     * Method to set the value of field created_at
     *
     * @param integer $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field created_by
     *
     * @param integer $created_by
     * @return $this
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Method to set the value of field modified_at
     *
     * @param integer $modified_at
     * @return $this
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    /**
     * Method to set the value of field modified_by
     *
     * @param integer $modified_by
     * @return $this
     */
    public function setModifiedBy($modified_by)
    {
        $this->modified_by = $modified_by;

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
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Returns the value of field introtext
     *
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * Returns the value of field fulltext
     *
     * @return string
     */
    public function getFulltext()
    {
        return $this->fulltext;
    }

    /**
     * Returns the value of field metadata
     *
     * @return string
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Returns the value of field category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the value of field hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Returns the value of field featured
     *
     * @return integer
     */
    public function getFeatured()
    {
        return $this->featured;
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
     * Returns the value of field view_level
     *
     * @return integer
     */
    public function getViewLevel()
    {
        return $this->view_level;
    }

    /**
     * Returns the value of field created_at
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field created_by
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Returns the value of field modified_at
     *
     * @return integer
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * Returns the value of field modified_by
     *
     * @return integer
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * @return int
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param integer $published_at
     * @return $this
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('content');
        $this->belongsTo('created_by', 'Core\Models\User', 'id', ['alias' => 'user', 'reusable' => true]);
        $this->belongsTo('modified_by', 'Core\Models\User', 'id', ['alias' => 'user', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'content';
    }

    public function beforeValidationOnCreate()
    {
        $this->setCreatedAt(time());
        $this->setCreatedBy($this->getDI()->getShared('auth')->getUserId());
    }

    public function beforeValidation()
    {
        if (empty($this->getAlias())) {
            $this->setAlias($this->getDI()->getShared('helper')->makeAlias($this->getTitle()));
        }
        $this->setPublishedAt(strtotime($this->getPublishedAt()));
    }

    public function beforeUpdate()
    {
        $this->setModifiedAt(time());
        $this->setModifiedBy($this->getDI()->getShared('auth')->getUserId());
    }
}
