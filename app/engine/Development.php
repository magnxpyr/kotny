<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Engine\Library\DebugWidget;
use Phalcon\Debug;

/**
 * Class Development
 * @package Engine
 */
class Development
{
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

        // Display the debug bar
        new DebugWidget($di);
    }
}