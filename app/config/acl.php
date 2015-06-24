<?php

return [
    'defaultAction' => \Phalcon\Acl::DENY,
    'resource' => [
        'core/index' => [
            'description' => '',
            'actions' => [
                '*',
                'index'
            ]
        ],
        'core/error' => [
            'description' => '',
            'actions' => [
                '*',
                'show404',
                'show503'
            ]
        ],
        'core/user' => [
            'description' => '',
            'actions' => [
                '*',
                'index',
                'login',
                'loginWithFacebook',
                'loginWithGoogle',
                'register',
                'logout',
                'confirmEmail',
                'forgotPassword',
                'resetPassword'
            ]
        ]
    ],
    'role' => [
        1 => [
            'allow' => [
                'core/index'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/error'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/user'     => [
                    'actions' => [
                        'login',
                        'loginWithFacebook',
                        'loginWithGoogle',
                        'register',
                        'logout',
                        'confirmEmail',
                        'forgotPassword',
                    ]
                ]
            ]
        ],
        2 => [
            'allow' => [
                'core/index'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/error'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/user'     => [
                    'actions' => [
                        '*',
                    ]
                ]
            ]
        ],
        3 => [
            'allow' => [
                'core/index'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/error'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/user'     => [
                    'actions' => [
                        '*',
                    ]
                ]
            ]
        ]
    ]
];