<?php

return array(
    'database' => array(
        'adapter'   => 'Mysql',
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => 'bingo',
        'dbname'    => 'cms',
        'charset'   => 'utf8',
    ),
    'app' => array(
        'cacheDir'      => ROOT_PATH . 'cache/',
        'baseUri'       => '/',
        'publicUri'     => '/',
        'cryptSalt'     => '',
        'development'   => true
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