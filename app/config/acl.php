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
        ],
        'core/admin-index' => [
            'description' => '',
            'actions' => ['*']
        ],
        'core/admin-file-manager' => [
            'description' => '',
            'actions' => ['*']
        ],
        'core/admin-menu-type' => [
            'description' => '',
            'actions' => [
                '*',
                'index',
                'new',
                'edit',
                'save',
                'delete'
            ]
        ],
        'core/admin-menu' => [
            'description' => '',
            'actions' => [
                '*',
                'index',
                'new',
                'edit',
                'update',
                'delete'
            ]
        ],
        'tools/admin-controllers' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/admin-migrations' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/admin-models' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/admin-modules' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/admin-scaffold' => [
            'description' => '',
            'actions' => [
                '*'
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
                'core/admin-index' => [
                    'actions' => ['*']
                ],
                'core/admin-file-manager' => [
                    'actions' => ['*']
                ],
                'core/admin-menu-type' => [
                    'actions' => ['*']
                ],
                'core/admin-menu' => [
                    'actions' => ['*']
                ],
                'core/index' => [
                    'actions' => ['*']
                ],
                'core/error' => [
                    'actions' => ['*']
                ],
                'core/user' => [
                    'actions' => ['*']
                ],
                'tools/admin-controllers' => [
                    'actions' => ['*']
                ],
                'tools/admin-migrations' => [
                    'actions' => ['*']
                ],
                'tools/admin-models' => [
                    'actions' => ['*']
                ],
                'tools/admin-modules' => [
                    'actions' => ['*']
                ],
                'tools/admin-scaffold' => [
                    'actions' => ['*']
                ]
            ]
        ]
    ]
];