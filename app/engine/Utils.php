<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

class Utils
{
    // Return 128 bits random string
    public static function gen_token($bytes = 16) {
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }
}