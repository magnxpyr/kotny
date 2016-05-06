<?php

return [
    'database' => [
        'adapter'   => 'Mysql',
        'host'      => 'localhost',
        'username'  => '',
        'password'  => '',
        'dbname'    => '',
    ],
    'app' => [
        'offline' => false,
        'timezone' => 'UTC',
        'siteName' => 'Magnxpyr Network',
        'siteNameLocation' => 1,
        'baseUri' => '/', // must end with trailing slash /
        'cryptKey' => '721a79281f408416', // Use your own 8 bits key!
        'development' => true,
        'aclAdapter' => 'memory', // database, memory
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
            'clientId' => '',
            'clientSecret' => '',
            'developerKey' => '' //api_key
        ]
    ],
    'mail' => [
        'driver' => 'mail', // smtp, mail, sendmail
        'from' => [
            'name' => 'Magnxpyr Network',
            'email' => 'contact@magnxpyr.com',
        ],
        'sendmail' => '/usr/sbin/sendmail -bs',
        'smtp' => [
            'host'       => 'smtp.gmail.com',
            'port'       => 465,
            'encryption' => 'ssl',
            'username'   => 'example@gmail.com',
            'password'   => 'your_password',
        ],
    ],
    'tools' => APP_PATH . 'config/tools.php'
];