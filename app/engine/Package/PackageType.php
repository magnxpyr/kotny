<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 29.04.2017
 * Time: 23:41
 */

namespace Engine\Package;


class PackageType
{
    const
        /**
         * Module package.
         */
        MODULE = 'module',

        /**
         * Plugin package.
         */
        PLUGIN = 'plugin',

        /**
         * Theme package.
         */
        THEME = 'theme',

        /**
         * Widget package.
         */
        WIDGET = 'widget',

        /**
         * Library package.
         */
        LIBRARY = 'library';

}