<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'bingo',
        'dbname'      => 'cms',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'modulesDir' => __DIR__ . '/../../app/modules/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'vendorDir'     => __DIR__ . '/../../app/vendor/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/',
    )
));
