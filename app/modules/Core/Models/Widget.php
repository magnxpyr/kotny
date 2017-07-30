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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $params;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $website;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field version
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Method to set the value of field author
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Method to set the value of field website
     *
     * @param string $website
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

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
     * Returns the value of field params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Returns the value of field author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the value of field website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
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
        $this->setSource('widget');
        $this->hasMany('id', 'Module\Core\Models\Migration', 'package_id', ['alias' => 'migration', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'widget';
    }

    public static function getCacheActiveWidgets()
    {
        return md5("model_widget.active");
    }

    /**
     * Get active widgets
     * @return \Phalcon\Mvc\Model
     */
    public static function getActiveWidgets()
    {
        return self::find([
            'conditions' => 'status = ?1',
            'bind' => [1 => 1],
            'columns' => ['name'],
            'cache' => [
                'key' => self::getCacheActiveWidgets(),
                'lifetime' => 3600
            ]
        ]);
    }

    /**
     * @param $name
     * @return bool
     */
    public static function isActive($name)
    {
        foreach (self::getActiveWidgets() as $widget) {
            if ($widget->name == $name) {
                return true;
            }
        }
        return false;
    }

}
