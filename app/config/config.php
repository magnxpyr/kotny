<?php

return array(
    'database' => array(
        'adapter'   => 'Mysql',
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => 'bingo',
        'dbname'    => 'cms'
    ),
    'app' => array(
        'offline' => false,
        'site_name' => 'Magnxpyr CMS',
        'site_name_location' => 0,
        'baseUri'       => '/',
        'cryptSalt'     => 'FBVtggIk5lAzEU9H',
        'development'   => true,
        'cacheDir'      => ROOT_PATH . 'cache/'
    ),
    'mail' => array(
        'fromName' => '',
        'fromEmail' => '',
        'smtp' => array(
            'server' => 'smtp.example.com',
            'port' => 587,
            'security' => 'tls',
            'username' => '',
            'password' => ''
        )
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/',
    )
);