<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

/**
 * Class ChangePasswordForm
 * @package Core\Forms
 */
class ChangePasswordForm extends Form
{
    /**
     * Initialize the Change Password Form
     */
    public function initialize()
    {
        // Current Password
        $currentPassword = new Password('currentPassword');
        $currentPassword->setFilters('string');
        $currentPassword->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Current password is required')
            ])
        ]);
        $this->add($currentPassword);

        // New password
        $password = new Password('password');
        $password->setLabel($this->t->_('Password'));
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Password is required')
            ]),
            new Confirmation([
                'message' => $this->t->_('Passwords don\'t match'),
                'with' => 'confirmPassword'
            ])
        ]);
        $this->add($password);

        // Confirm new password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setFilters('string');
        $repeatPassword->setLabel($this->t->_('Repeat Password'));
        $repeatPassword->addValidators([
            new PresenceOf([
                'message' => $this->t->_('Confirmation password is required')
            ])
        ]);
        $this->add($repeatPassword);

        // Submit button
        $this->add(new Submit('Send', [
            'class' => 'btn btn-primary'
        ]));
    }
}