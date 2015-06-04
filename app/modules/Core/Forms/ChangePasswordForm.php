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
class ChangePasswordForm extends Form {

    /**
     * Initialize the Change Password Form
     */
    public function initialize() {

        // Current Password
        $currentPassword = new Password('currentPassword');
        $currentPassword->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Current password is required']
            ))
        ));
        $this->add($currentPassword);

        // New password
        $password = new Password('password');
        $password->setLabel($this->t['Password']);
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Password is required']
            )),
            new Confirmation(array(
                'message' => $this->t['Passwords don\'t match'],
                'with' => 'confirmPassword'
            ))
        ));
        $this->add($password);

        // Confirm new password
        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel($this->t['Repeat Password']);
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['Confirmation password is required']
            ))
        ));
        $this->add($repeatPassword);

        // Submit button
        $this->add(new Submit('Send', array(
            'class' => 'btn btn-primary'
        )));
    }
}