<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

error_reporting(E_ALL);

/**
 * Check phalcon framework installation.
 */
if (!extension_loaded('phalcon')) {
    printf('Install Phalcon framework %s', '>1.3.2');
    exit(1);
}

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app/');
define('PUBLIC_PATH', __DIR__);

try {
    require_once APP_PATH . 'Bootstrap.php';
    $bootstrap = new Bootstrap();
    $bootstrap->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
