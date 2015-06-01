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
        $name = new Text('username');
        $name->setLabel('Username');
        $name->setFilters(array('alpha'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your user name'
            )),
        ));
        $this->add($name);

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