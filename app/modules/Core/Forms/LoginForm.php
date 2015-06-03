<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Identical;

/**
 * Class LoginForm
 * @package Core\Forms
 */
class LoginForm extends Form {

    /**
     * Initialize the Login Form
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

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            ))
        ));
        $this->add($password);

        // Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));
        $remember->setLabel('Remember me');
        $this->add($remember);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        )));
        $this->add($csrf);

        // Submit
        $this->add(new Submit('Login', array(
            'class' => 'btn btn-success'
        )));
    }
}