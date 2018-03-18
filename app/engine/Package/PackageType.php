<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Package;

use Engine\Traits\ConstTrait;

/**
 * Class PackageType
 * @package Engine\Package
 */
class PackageType
{
    use ConstTrait;

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
}