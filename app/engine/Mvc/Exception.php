<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;
use Engine\Behavior\DiBehavior;
use Throwable;

/**
 * Class Exception
 * @package Engine\Mvc
 */
class Exception extends \Exception
{
    use DiBehavior;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->getDi()->get('logger')->error($message);
    }
}