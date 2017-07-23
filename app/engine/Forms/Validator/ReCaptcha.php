<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Forms\Validator;

use Engine\Behavior\DiBehavior;
use Engine\Meta;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use ReCaptcha\ReCaptcha as GoogleReCaptcha;

/**
 * Class Captcha
 * @package Engine\Forms\Element
 */
class ReCaptcha extends Validator
{
    use Meta,
        DiBehavior;

    /**
     * Executes the captcha validation
     *
     * @param \Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate(Validation $validator, $attribute)
    {
        $di = $this->getDI();
        $reCaptcha = new GoogleReCaptcha($di->get("config")->api->google->recaptcha->secretKey);
        $value = isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : null;
        $resp = $reCaptcha->verify($value, $di->get("auth")->getRealIp());
        if ($resp->isSuccess()) {
            return true;
        } else {
            $message = $this->getOption("message");
            if (!$message) {
                $errors = $resp->getErrorCodes();
                $message = implode(",", $errors);
            }

            $validator->appendMessage(
                new Message($message, $attribute)
            );

            return false;
        }
    }
}