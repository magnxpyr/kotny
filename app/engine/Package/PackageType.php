<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Package;


use ReflectionClass;

class PackageType
{
    const
        /**
         * Module package.
         */
        MODULE = 'module',

        /**
         * Widget package.
         */
        WIDGET = 'widget';

//        /**
//         * Plugin package.
//         */
//        PLUGIN = 'plugin',
//
//        /**
//         * Theme package.
//         */
//        THEME = 'theme',
//
//        /**
//         * Library package.
//         */
//        LIBRARY = 'library';

    public static function getConstants()
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }

}