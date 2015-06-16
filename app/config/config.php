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
    'connectors' => [
        'facebook' => [
            'appId' => '',
            'secret' => ''
        ],
        'google' => [
            'clientId' => '679315718466-qq2rqli8i3mm84ouhlgosei7n3hvlro6.apps.googleusercontent.com',
            'clientSecret' => '8NQ8c0WI-Ct9QLhnCpB7iBR0',
            'developerKey' => 'AIzaSyDCl1dvNu3Cy00w4FijyjzqxOVv2i7LN3Y' //api_key
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