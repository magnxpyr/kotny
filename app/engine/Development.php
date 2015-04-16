<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Development {

    public function __construct() {
        // Display all errors
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        // Load pretty exceptions
        $p = new \Phalcon\Utils\PrettyExceptions();
        // Sets the exception handler
        set_exception_handler(function($e) use ($p){
            return $p->handle($e);
        });

        // Sets the error handler
        set_error_handler(function($errorCode, $errorMessage, $errorFile, $errorLine) use ($p) {
            return $p->handleError($errorCode, $errorMessage, $errorFile, $errorLine);
        });
    }
}