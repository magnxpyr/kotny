<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

/**
 * Class ForgotPasswordForm
 * @package Core\Forms
 */
class ForgotPasswordForm extends Form {

    /**
     * Initialize the Forgot Password Form
     */
    public function initialize() {

        // Email
        $email = new Text('email', array(
            'placeholder' => $this->t['Email']
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $this->t['The e-mail is required']
            )),
            new Email(array(
                'message' => $this->t['The e-mail is not valid']
            ))
        ));
        $this->add($email);

        // Submit button
        $this->add(new Submit($this->t['Send'], array(
            'class' => 'btn btn-primary'
        )));
    }
}