<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Identical;

/**
 * Class LoginForm
 * @package Core\Forms
 */
class LoginForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();

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
                'message' => $this->t->_('%field% is not valid', ['field' => $this->t->_('Username')])
            ])
        ]);
        $this->add($username);

        // Password
        $password = new Password('password', [
            'placeholder' => $this->t->_('Password'),
            'class' => 'form-control'
        ]);
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Password')])
            ])
        ]);
        $this->add($password);

        // Remember
        $remember = new Check('remember', [
            'value' => 1,
            'class' => 'styled'
        ]);
        $remember->setFilters('int');
        $remember->setLabel($this->t->_('Remember me'));
        $this->add($remember);

        // Submit
        $this->add(new Submit('login', [
            'value' => $this->t->_('Login'),
            'class' => 'btn btn-primary btn-block btn-flat'
        ]));
    }
}