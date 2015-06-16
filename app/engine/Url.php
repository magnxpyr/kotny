<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 16.06.2015
 * Time: 16:11
 */

namespace Engine;

/**
 * Class Url
 * @package Engine
 */
class Url extends \Phalcon\Mvc\Url
{
    /**
     * Return full url
     *
     * @param string $path
     * @return string
     */
    public function getUri($path) {
        $protocol  = 'http://';
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https://';
        }
        return $protocol . $_SERVER['HTTP_HOST'] . $path;
    }
}