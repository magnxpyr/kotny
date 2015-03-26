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
        'baseUri'       => '/cms/',
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
    )
);