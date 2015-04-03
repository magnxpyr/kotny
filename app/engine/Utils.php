<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Utils {
    public static function camelize($module) {
        $tmpModuleNameArr = explode('-', $module);
        $moduleName = '';
        foreach ($tmpModuleNameArr as $part) {
            $moduleName .= \Phalcon\Text::camelize($part);
        }
        return $moduleName;
    }

    // Return 128 bits random string
    function gen_token($bytes = 16) {
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }
}