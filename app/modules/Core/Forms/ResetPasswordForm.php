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
 * Class ResetPasswordForm
 * @package Core\Forms
 */
class ResetPasswordForm extends Form
{
    /**
     * Initialize the Reset Password Form
     */
    public function initialize()
    {
        // Password
        $password = new Password('password');
        $password->setLabel($this->t->_('Password'));
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Password is required')
            ])
        ]);
        $this->add($password);

        // Confirm Password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel($this->t->_('Repeat Password'));
        $repeatPassword->setFilters('string');
        $repeatPassword->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Confirmation password is required')
            ])
        ]);
        $this->add($repeatPassword);

        // Submit
        $this->add(new Submit($this->t->_('Send'), [
            'class' => 'btn btn-success'
        ]));
    }
}