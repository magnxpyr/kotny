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
        'tools/controllers' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/migrations' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/models' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/modules' => [
            'description' => '',
            'actions' => [
                '*'
            ]
        ],
        'tools/scaffold' => [
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
                'core/index'    => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/admin-menu-type' => [
                    'actions' => [
                        '*'
                    ]
                ],
                'core/admin-menu' => [
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
                        '*'
                    ]
                ],
                'tools/controllers' => [
                    'actions' => [
                        '*'
                    ]
                ],
                'tools/migrations' => [
                    'actions' => [
                        '*'
                    ]
                ],
                'tools/models' => [
                    'actions' => [
                        '*'
                    ]
                ],
                'tools/modules' => [
                    'actions' => [
                        '*'
                    ]
                ],
                'tools/scaffold' => [
                    'actions' => [
                        '*'
                    ]
                ]
            ]
        ]
    ]
];