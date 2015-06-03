<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends Model {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $auth_token;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $reset_token;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var integer
     */
    protected $role;

    /**
     * @var integer
     */
    protected $status;

    /**
     * @var integer
     */
    protected $created_at;

    /**
     * @var integer
     */
    protected $visited_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field username
     *
     * @param string $username
     * @return $this
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Method to set the value of field auth_key
     *
     * @param string $auth_key
     * @return $this
     */
    public function setAuthToken($auth_token) {
        $this->auth_token = $auth_token;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field reset_token
     *
     * @param string $reset_token
     * @return $this
     */
    public function setResetToken($reset_token) {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field role
     *
     * @param integer $role
     * @return $this
     */
    public function setRole($role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field register_date
     *
     * @param integer $register_date
     * @return $this
     */
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field last_visit_date
     *
     * @param integer $last_visit_date
     * @return $this
     */
    public function setVisitedAt($visited_at) {
        $this->visited_at = $visited_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the value of field username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Returns the value of field auth_key
     *
     * @return string
     */
    public function getAuthToken() {
        return $this->auth_token;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Returns the value of field reset_token
     *
     * @return string
     */
    public function getResetToken() {
        return $this->reset_token;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Returns the value of field role
     *
     * @return integer
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Returns the value of field register_date
     *
     * @return integer
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Returns the value of field last_visit_date
     *
     * @return integer
     */
    public function getVisitedAt() {
        return $this->visited_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize() {
        $this->setSource('user');
    }

    /**
     * Get table source
     * @return string
     */
    public function getSource() {
        return 'user';
    }

    /**
     * Validations and business logic
     */
    public function validation() {
        $this->validate(
            new Email(array(
                'field' => 'email',
                'required' => true,
                'message' => 'Email address is not valid'
            ))
        );
        $this->validate(
            new Uniqueness(array(
                'field' => 'email',
                'message' => 'Email already exists'
            ))
        );
        $this->validate(
            new Uniqueness(array(
                'field' => 'username',
                'message' => 'Username already exists'
            ))
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Send an e-mail to users allowing him/her to reset his/her password
     */
    public function afterCreate() {
        /*
        $this->getDI()
            ->getMail()
            ->send(array(
                $this->user->email => $this->user->name
            ), "Reset your password", 'reset', array(
                'resetUrl' => '/reset-password/' . $this->code . '/' . $this->user->email
            ));
        */
    }
}
