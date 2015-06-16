<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

/**
 * Class Security
 * @package Engine
 */
class Security extends \Phalcon\Security
{
    /**
     * Return 128 bits random string
     *
     * @param int $bytes
     * @return string
     */
    public static function generateToken($bytes = 32)
    {
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }

    /**
     * Timing attack safe string comparison for php >= 5.3
     *
     * @param $known_string
     * @param $user_string
     * @return bool
     */
    public static function hash_equals($known_string, $user_string)
    {
        if(!function_exists('hash_equals')) {
            $ret = strlen($known_string) ^ strlen($user_string);
            $ret |= array_sum(unpack("C*", $known_string ^ $user_string));
            return !$ret;
        } else {
            return (hash_equals($known_string, $user_string));
        }
    }
}