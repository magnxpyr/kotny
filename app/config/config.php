<?php

return [
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'dbname' => '',
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
        'cache' => [
            'adapter' => 'file', // file, memcache, memcached
            'host' => '',
            "port" => '',
        ],
        'meta' => [
            'description' => '',
            'keywords' => '',
            'robots' => '', // 'Index, Follow', 'No index, follow', 'Index, no follow', 'No index, no follow'
            'contentRights' => '',
            'showAuthor' => false
        ],
        'cookie' => [
            'name' => 'mgRm',
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'expire' => 86400 * 30
        ]
    ],
    'api' => [
        'facebook' => [
            'appId' => '',
            'secret' => ''
        ],
        'google' => [
            'oauth' => [
                'clientId' => '',
                'clientSecret' => '',
                'developerKey' => '' //api_key
            ],
            'recaptcha' => [
                'siteKey' => '',
                'secretKey' => ''
            ]
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
            'host' => 'smtp.gmail.com',
            'port' => 465,
            'encryption' => 'ssl',
            'username' => 'example@gmail.com',
            'password' => 'your_password',
        ],
    ],
    'tools' => APP_PATH . 'config/tools.php'
];