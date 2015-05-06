<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Phalcon\Debug;

class Development {

    /**
     * Initialize the development tools.
     * Set pretty exceptions, debug widget and disable error handler
     *
     * @param \Phalcon\Di $di
     */
    public function __construct($di) {
        // Display all errors
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $debug = new Debug();
        $debug->listen();
        /*
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

        new \PDW\DebugWidget($di);
        */
    }
}