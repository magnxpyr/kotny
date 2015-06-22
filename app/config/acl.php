<?php

return [
    'defaultAction' => \Phalcon\Acl::DENY,
    'resource' => [
        'core/index' => [
            'description' => '',
            'actions' => [
                'index'
            ]
        ],
        'core/error' => [
            'description' => '',
            'actions' => [
                'show404',
                'show503'
            ]
        ],
        'core/user' => [
            'description' => '',
            'actions' => [
                'login',
                'loginWithFacebook',
                'loginWithGoogle',
                'register',
                'logout',
                'confirmEmail',
                'forgot-password',
                'resetPassword'
            ]
        ]
    ],
    'role' => [
        1 => [
            'allow' => [
                'core/index'    => '*',
                'core/error'    => '*',
                'core/user'     => '*'
            ]
        ],
        2 => [
            'allow' => []
        ],
        3 => [
            'allow' => '*'
        ]
    ]

];