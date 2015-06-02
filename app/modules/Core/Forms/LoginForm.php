<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alpha;

/**
 * Class LoginForm
 * @package Core\Forms
 */
class LoginForm extends Form {
    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null) {

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
    }
}