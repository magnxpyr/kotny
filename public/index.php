<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

// define global variables
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__).DS);
define('APP_PATH', ROOT_PATH . 'app/');
define('MEDIA_PATH', ROOT_PATH . 'media/');
define('PUBLIC_PATH', __DIR__);

// Check phalcon framework installation.
if (!extension_loaded('phalcon')) {
    printf('Install Phalcon framework %s', '> 1.3.x');
    exit(1);
}

try {
    require_once APP_PATH . 'engine/Bootstrap.php';

    $bootstrap = new Bootstrap();
    $bootstrap->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
