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
        module = 'module',

        /**
         * Widget package.
         */
        widget = 'widget';

//        /**
//         * Plugin package.
//         */
//        plugin = 'plugin',
//
//        /**
//         * Theme package.
//         */
//        theme = 'theme',
//
//        /**
//         * Library package.
//         */
//        library = 'library';
}