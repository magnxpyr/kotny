<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Core\Models\User;
use Phalcon\Forms\Element\Check;
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
class RegisterForm extends Form
{
    /**
     * Initialize the Register Form
     */
    public function initialize()
    {
        // Username
        $username = new Text('username', [
            'placeholder' => $this->t->_('Username'),
            'class' => 'form-control'
        ]);
        $username->setFilters('alphanum');
        $username->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Please enter your desired username')
            ]),
            new Alpha([
                'message' => $this->t->_('Username is not valid')
            ])
        ]);
        $this->add($username);

        // Email
        $email = new Text('email', [
            'placeholder' => $this->t->_('Email'),
            'class' => 'form-control'
        ]);
        $email->setFilters('email');
        $email->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Email is required')
            ]),
            new Email([
                'message' => $this->t->_('Email is not valid')
            ])
        ]);
        $this->add($email);

        // Password
        $password = new Password('password', [
            'placeholder' => $this->t->_('Password'),
            'class' => 'form-control'
        ]);
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Password is required')
            ])
        ]);
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword', [
            'placeholder' => $this->t->_('Repeat Password'),
            'class' => 'form-control'
        ]);
        $repeatPassword->setFilters('string');
        $repeatPassword->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Confirmation password is required')
            ])
        ]);
        $this->add($repeatPassword);

        // Submit
        $this->add(new Submit('submit', [
            'value' => $this->t->_('Register'),
            'class' => 'btn btn-primary btn-block btn-flat'
        ]));
    }
}