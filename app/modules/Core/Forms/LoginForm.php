<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Element\Captcha;
use Engine\Forms\Validator\ReCaptcha;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Alpha;

/**
 * Class LoginForm
 * @package Module\Core\Forms
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

        // Captcha
        if ($this->auth->isUserRequestThrottled()) {
            $captcha = new Captcha('captcha');
            $captcha->addValidators([
                new ReCaptcha([
                    'message' => $this->t->_('Invalid captcha')
                ])
            ]);
            $this->add($captcha);
        }

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