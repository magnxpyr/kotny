<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Engine\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

/**
 * Class ForgotPasswordForm
 * @package Core\Forms
 */
class ForgotPasswordForm extends Form
{
    /**
     * Initialize the Forgot Password Form
     */
    public function initialize()
    {
        // Email
        $email = new Text('email', [
            'placeholder' => $this->t->_('Email'),
            'class' => 'form-control'
        ]);
        $email->setFilters('email');
        $email->addValidators([
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Email')])
            ]),
            new Email([
                'message' => $this->t->_('Email is not valid')
            ])
        ]);
        $this->add($email);

        // Submit button
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary btn-block btn-flat',
            'value' => $this->t->_('Send')
        ]));
    }
}