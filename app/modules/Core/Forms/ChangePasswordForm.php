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
 * Phalcon\UserPlugin\Forms\User\ChangePasswordForm
 */
class ChangePasswordForm extends Form {

    /**
     * Initialize the Change Password Form
     */
    public function initialize() {

        // Current Password
        $currentPassword = new Password('currentPassword');
        $currentPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Current password is required'
            ))
        ));
        $this->add($currentPassword);

        // New password
        $password = new Password('password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            )),
            new Confirmation(array(
                'message' => 'Password doesn\'t match confirmation',
                'with' => 'confirmPassword'
            ))
        ));
        $this->add($password);

        // Confirm new password
        $confirmPassword = new Password('confirmPassword');
        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'The confirmation password is required'
            ))
        ));
        $this->add($confirmPassword);

        // Submit button
        $this->add(new Submit('Send', array(
            'class' => 'btn btn-primary'
        )));
    }
}