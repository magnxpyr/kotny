<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Traits;

use ReflectionClass;

/**
 * Trait ConstTrait
 * @package Engine\Traits
 */
trait ConstTrait
{
    public static function getConstants()
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }

    public static function getConstant($string)
    {
        return constant(__CLASS__ . "::" . $string);
    }
}