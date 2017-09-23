<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Forms\Element;

use Engine\Behavior\DiBehavior;
use Engine\Meta;
use Phalcon\Forms\Element;

/**
 * Class Captcha
 * @package Engine\Forms\Element
 */
class Captcha extends Element
{
    use Meta,
        DiBehavior;

    public function render($attributes = false)
    {
        $di = $this->getDI();
        $di->get('assets')->collection('footer-js')->addJs("https://www.google.com/recaptcha/api.js", false);
        return "<div class=\"g-recaptcha\" data-sitekey=\"" . $di->get('config')->api->google->recaptcha->siteKey . "\"></div>";
    }
}