<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Engine\Mvc\Model;
use Phalcon\Mvc\Model\EagerLoadingTrait;

class UserAuthTokens extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $selector;

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $user_id;

    /**
     * @var string
     */
    private $expires;

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
     * Method to set the value of field selector
     *
     * @param string $selector
     * @return $this
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * Method to set the value of field token
     *
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Method to set the value of field expires
     *
     * @param string $expires
     * @return $this
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
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
     * Returns the value of field selector
     *
     * @return string
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Returns the value of field token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field expires
     *
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('user_auth_tokens');
        $this->belongsTo('user_id', 'Core\Models\User', 'id', ['alias' => 'user', 'reusable' => true]);
    }

    public function getSource()
    {
        return 'user_auth_tokens';
    }

}
