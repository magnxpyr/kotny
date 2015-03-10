<?php

$config = array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'bingo',
        'dbname'      => 'cms',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'modulesDir' => APP_PATH . 'modules/',
        'modelsDir'      => APP_PATH . 'models/',
        'viewsDir'       => APP_PATH . 'views/',
        'pluginsDir'     => APP_PATH . 'plugins/',
        'engineDir'     => APP_PATH . 'engine/Loader',
        'vendorDir'     => APP_PATH . 'vendor/',
        'cacheDir'       => APP_PATH . 'cache/',
        'baseUri'        => '/',
    )
);

$modules_list = require_once 'modules.php';
require_once APP_PATH . 'engine/Loader.php';
$modules = new \Engine\Modules();
$modules_config = $modules->modulesConfig($modules_list);
$config = array_merge_recursive($config, $modules_config);
//print_r($config);exit;
return new \Phalcon\Config($config);