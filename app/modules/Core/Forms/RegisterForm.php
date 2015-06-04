<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Core\Models\User;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * Class RegisterForm
 * @package Core\Forms
 */
class RegisterForm extends Form {

    /**
     * Initialize the Register Form
     */
    public function initialize() {

        // Username
        $username = new Text('username');
        $username->setLabel('Username');
        $username->setFilters('alphanum');
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your desired user name'
            )),
            new Alpha(array(
                'message' => 'User name is not valid'
            ))
        ));
        $this->add($username);

        // Email
        $email = new Text('email');
        $email->setLabel('Email');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'Email is required'
            )),
            new Email(array(
                'message' => 'Email is not valid'
            ))
        ));
        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            ))
        ));
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel('Repeat Password');
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Confirmation password is required'
            ))
        ));
        $this->add($repeatPassword);

        // Submit
        $this->add(new Submit('Register', array(
            'class' => 'btn btn-success'
        )));
    }
}