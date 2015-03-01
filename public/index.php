<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

error_reporting(E_ALL);

try {
    require_once '../app/Bootstrap.php';
    $bootstrap = new Bootstrap();
    $bootstrap->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
