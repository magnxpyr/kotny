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
        'baseUri'       => '/', // must end with trailing slash /
        'cryptKey'     => '9C689CBCFBCA3BBFE0EDDF2F33D63298AC4D6FE9D0FF83FFA00D13CEE331F403', // Use your own key!
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