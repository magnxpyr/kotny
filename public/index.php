<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

// define global variables
define('MG_VERSION', '1.0.0-SNAPSHOT');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);
define('PUBLIC_PATH', __DIR__ . DS);
define('APP_PATH', ROOT_PATH . 'app/');
define('CONFIG_PATH', APP_PATH . 'config/');
define('LOGS_PATH', ROOT_PATH . 'logs/');
define('MEDIA_PATH', PUBLIC_PATH . 'media/');
define('THEMES_PATH', APP_PATH . 'themes/');
define('MODULES_PATH', APP_PATH . 'modules/');
define('WIDGETS_PATH', APP_PATH . 'widgets/');
define('CACHE_PATH', ROOT_PATH . 'cache/');
define('TEMP_PATH', CACHE_PATH . 'tmp/');

// Check phalcon framework installation.
if (!extension_loaded('phalcon') || phpversion("phalcon") < 3) {
    printf('Install Phalcon PHP Framework %s<br />', '>= 3.x.x');
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR;

    $phalconSo = getPhalconSo();
    $error = false;
    switch ($phalconSo) {
        case "php":
            echo "Your PHP version needs to be >= 5.6<br />";
            $error = true;
            break;
        case "cos":
            $error = true;
            break;
    }

    if (!$error) {
        $url = "https://github.com/magnxpyr/phalcon.so/releases/download/phalcon3.2/";
        echo "We detected a possible compatible phalcon.so: <a href='" . $url . $phalconSo ."'>$phalconSo</a><br />";
    }
    echo "If you're running on cPanel, check the <a href='https://github.com/magnxpyr/phalcon.so' target='_blank'>following documentation</a> on how you could setup Phalcon PHP Framework.<br />";
    echo "If you have your own server, check the <a href='https://phalconphp.com/en/download/linux' target='_blank'>documentation</a> on how to get started.";
    exit(1);
}

//if (is_dir(ROOT_PATH . 'installer')) {
//    require_once ROOT_PATH . 'installer/Bootstrap.php';
//    $bootstrap = new Bootstrap();
//    $bootstrap->run();
//} else {
    require_once APP_PATH . 'engine/Bootstrap.php';
    $bootstrap = new Bootstrap();
    $bootstrap->run();
//}

function getPhalconSo()
{
    $phpVersion = substr(phpversion(), 0, 3);
    $kernel = php_uname("r");
    $kernelVersion = substr($kernel, 0, 1);

    $os = null;
    if (strpos($kernel, "el6") !== false) {
        $os = "el6";
    } elseif (strpos($kernel, "el7") !== false) {
        $os = "el7";
    }


    $url = "phalcon";
    switch ((string) $phpVersion) {
        case "5.6": $url .= "php56"; break;
        case "7.0": $url .= "php70"; break;
        case "7.1": $url .= "php71"; break;
        case "7.2": $url .= "php72"; break;
        default: return "php";
    }

    switch ($os) {
        case "el6": $url .= "cos6"; break;
        case "el7": $url .= "cos7"; break;
        default:
            switch ($kernelVersion) {
                case 2: $url .= "cos6"; break;
                case 3: $url .= "cos7"; break;
                default: return "cos";
            }
    }

    $url .= ".so";

    return $url;
}

