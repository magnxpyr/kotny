<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

// define global variables
define('MG_VERSION', '0.1.0');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);
define('PUBLIC_PATH', __DIR__ . DS);
define('APP_PATH', ROOT_PATH . 'app/');
define('MEDIA_PATH', PUBLIC_PATH . 'media/');

// Check phalcon framework installation.
if (!extension_loaded('phalcon')) {
    printf('Install Phalcon framework %s', '> 3.0.x');
    exit(1);
}

require_once APP_PATH . 'engine/Bootstrap.php';

$bootstrap = new Bootstrap();
$bootstrap->run();

