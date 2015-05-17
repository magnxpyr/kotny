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
        'site_name' => 'Magnxpyr CMS',
        'site_name_location' => 0,
        'baseUri'       => '/', // must end with trailing slash /
        'cryptSalt'     => 'FBVtggIk5lAzEU9H',
        'development'   => true,
        'cacheDir'      => ROOT_PATH . 'cache/'
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