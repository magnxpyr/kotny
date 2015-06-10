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
class RegisterForm extends Form
{
    /**
     * Initialize the Register Form
     */
    public function initialize()
    {
        // Username
        $username = new Text('username');
        $username->setLabel($this->t['Username']);
        $username->setFilters('alphanum');
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Please enter your desired username']
            )),
            new Alpha(array(
                'message' => $this->t['Username is not valid']
            ))
        ));
        $this->add($username);

        // Email
        $email = new Text('email');
        $email->setLabel($this->t['Email']);
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Email is required']
            )),
            new Email(array(
                'message' => $this->t['Email is not valid']
            ))
        ));
        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel($this->t['Password']);
        $password->setFilters('string');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Password is required']
            ))
        ));
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel($this->t['Repeat Password']);
        $repeatPassword->setFilters('string');
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Confirmation password is required']
            ))
        ));
        $this->add($repeatPassword);

        // Submit
        $this->add(new Submit($this->t['Register'], array(
            'class' => 'btn btn-success'
        )));
    }
}