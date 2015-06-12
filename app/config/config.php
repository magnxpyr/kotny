<?php

return [
    'database' => [
        'adapter'   => 'Mysql',
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => 'bingo',
        'dbname'    => 'cms',
    ],
    'app' => [
        'offline' => false,
        'site_name' => 'Magnxpyr Network',
        'site_name_location' => 1,
        'baseUri' => '/', // must end with trailing slash /
        'cryptKey' => '721a79281f408416', // Use your own 8 bits key!
        'development' => true,
        'cacheDir' => ROOT_PATH . 'cache/',
        'cookie' => [
            'name' => 'mgRm',
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'expire' => 86400 * 30
        ]
    ],
    'mail' => [
        'fromName' => '',
        'fromEmail' => '',
        'smtp' => [
            'server' => 'smtp.example.com',
            'port' => 587,
            'security' => 'tls',
            'username' => '',
            'password' => ''
        ]
    ],
    'tools' => APP_PATH . 'config/tools.php'
];