<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Models;

use Engine\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * Class User
 * @package Module\Core\Models
 */
class User extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $auth_token;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $gplus_id;

    /**
     * @var string
     */
    private $facebook_id;

    /**
     * @var string
     */
    private $gplus_name;

    /**
     * @var string
     */
    private $facebook_name;

    /**
     * @var string
     */
    private $gplus_data;

    /**
     * @var string
     */
    private $facebook_data;

    /**
     * @var string
     */
    private $reset_token;

    /**
     * @var integer
     */
    private $role_id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var integer
     */
    private $created_at;

    /**
     * @var integer
     */
    private $visited_at;

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
     * Method to set the value of field username
     *
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Method to set the value of field auth_token
     *
     * @param string $auth_token
     * @return $this
     */
    public function setAuthToken($auth_token)
    {
        $this->auth_token = $auth_token;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field gplus_id
     *
     * @param string $gplus_id
     * @return $this
     */
    public function setGplusId($gplus_id)
    {
        $this->gplus_id = $gplus_id;

        return $this;
    }

    /**
     * Method to set the value of field facebook_id
     *
     * @param string $facebook_id
     * @return $this
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Method to set the value of field gplus_name
     *
     * @param string $gplus_name
     * @return $this
     */
    public function setGplusName($gplus_name)
    {
        $this->gplus_name = $gplus_name;

        return $this;
    }

    /**
     * Method to set the value of field facebook_name
     *
     * @param string $facebook_name
     * @return $this
     */
    public function setFacebookName($facebook_name)
    {
        $this->facebook_name = $facebook_name;

        return $this;
    }

    /**
     * Method to set the value of field gplus_data
     *
     * @param string $gplus_data
     * @return $this
     */
    public function setGplusData($gplus_data)
    {
        $this->gplus_data = $gplus_data;

        return $this;
    }

    /**
     * Method to set the value of field facebook_data
     *
     * @param string $facebook_data
     * @return $this
     */
    public function setFacebookData($facebook_data)
    {
        $this->facebook_data = $facebook_data;

        return $this;
    }

    /**
     * Method to set the value of field reset_token
     *
     * @param string $reset_token
     * @return $this
     */
    public function setResetToken($reset_token)
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * Method to set the value of field role_id
     *
     * @param integer $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Method to set the value of field visited_at
     *
     * @param integer $visited_at
     * @return $this
     */
    public function setVisitedAt($visited_at)
    {
        $this->visited_at = $visited_at;

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
     * Returns the value of field username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the value of field auth_token
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->auth_token;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field gplus_id
     *
     * @return string
     */
    public function getGplusId()
    {
        return $this->gplus_id;
    }

    /**
     * Returns the value of field facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Returns the value of field gplus_name
     *
     * @return string
     */
    public function getGplusName()
    {
        return $this->gplus_name;
    }

    /**
     * Returns the value of field facebook_name
     *
     * @return string
     */
    public function getFacebookName()
    {
        return $this->facebook_name;
    }

    /**
     * Returns the value of field gplus_data
     *
     * @return string
     */
    public function getGplusData()
    {
        return $this->gplus_data;
    }

    /**
     * Returns the value of field facebook_data
     *
     * @return string
     */
    public function getFacebookData()
    {
        return $this->facebook_data;
    }

    /**
     * Returns the value of field reset_token
     *
     * @return string
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Returns the value of field created_at
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field visited_at
     *
     * @return integer
     */
    public function getVisitedAt()
    {
        return $this->visited_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', Content::class, 'created_by', ['alias' => 'contentCreated', 'reusable' => true]);
        $this->hasMany('id', Content::class, 'modified_by', ['alias' => 'contentModified', 'reusable' => true]);
        $this->hasMany('id', UserAuthTokens::class, 'user_id', ['alias' => 'userAuthTokens', 'reusable' => true]);
        $this->hasOne('id', UserEmailConfirmations::class, 'user_id', ['alias' => 'userEmailConfirmations', 'reusable' => true]);
        $this->hasOne('id', UserResetPasswords::class, 'user_id', ['alias' => 'userResetPasswords', 'reusable' => true]);
    }

    public static function getCacheRoleIdKey($id)
    {
        return md5("model_user.role_id.$id");
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {
        $validator = new Validation();
        $validator->add('email', new Email([
            'required' => true,
            'message' => $this->getDI()->get('t')->_('Email address is not valid')
        ]));
        $validator->add('email', new Uniqueness([
            'message' => $this->getDI()->get('t')->_('Email already exists')
        ]));
        $validator->add('username', new Uniqueness([
            'message' => $this->getDI()->get('t')->_('Username already exists')
        ]));
        return $this->validate($validator);
    }

    /**
     * Set visited at before any update
     */
    public function beforeUpdate()
    {
        $this->setVisitedAt(time());
        $this->getDI()->getShared('cache')->delete(self::getCacheRoleIdKey($this->getRoleId()));
    }

    /**
     * Send an e-mail to users allowing them to activate their account or reset the password
     */
    public function afterCreate()
    {
        if ($this->getStatus() != 0) {
            return;
        }
        $confirmation = new UserEmailConfirmations();
        $confirmation->setUserId($this->getId());
        $confirmation->save();
    }

    /**
     * Get role id and cache it for future use
     *
     * @param $id
     * @return Resultset|int
     */
    public static function getRoleById($id)
    {
        $role = self::findFirst([
            'conditions' => 'id = ?1',
            'bind' => [1 => $id],
            'columns' => ['role_id'],
            'cache' => [
                'key' => self::getCacheRoleIdKey($id),
                'lifetime' => 3600
            ]
        ]);
        if ($role) {
            return $role->role_id;
        } else {
            return 1;
        }
    }
}
