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
class LoginForm extends Form
{
    /**
     * @var null $lastCsrfValue
     */
    private $lastCsrfValue = null;

    /**
     * Get last Csrf value
     */
    public function getCsrf()
    {
        return ($this->lastCsrfValue !== null) ? $this->lastCsrfValue : $this->lastCsrfValue = $this->security->getToken();
    }

    /**
     * Initialize the Login Form
     */
    public function initialize()
    {
        // Username
        $username = new Text('username');
        $username->setLabel($this->t->_('Username'));
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

        // Password
        $password = new Password('password');
        $password->setFilters('string');
        $password->setLabel($this->t->_('Password'));
        $password->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Password is required')
            ])
        ]);
        $this->add($password);

        // Remember
        $remember = new Check('remember', [
            'value' => 1
        ]);
        $remember->setFilters('int');
        $remember->setLabel($this->t->_('Remember me'));
        $this->add($remember);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => $this->t->_('CSRF validation failed')
        ]));
        $this->add($csrf);

        // Submit
        $this->add(new Submit($this->t->_('Login'), [
            'class' => 'btn btn-success'
        ]));
    }
}