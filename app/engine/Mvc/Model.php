<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Engine\Meta;
use Phalcon\Mvc\Model\EagerLoadingTrait;

class Model extends \Phalcon\Mvc\Model
{
    use EagerLoadingTrait,
        Meta;

}