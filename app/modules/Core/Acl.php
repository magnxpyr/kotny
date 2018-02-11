<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

return [
    'allow' => [
        'guest' => [
            'user' => [
                'login',
                'loginWithFacebook',
                'loginWithGoogle',
                'register',
                'confirmEmail',
                'forgotPassword',
                'resetPassword'
            ],
        ],
        'user' => [
            'user' => ['logout']
        ],
        'editor' => [
            'admin-index' => ['*'],
            'admin-content' => ['*'],
            'admin-category' => ['*'],
            'admin-file-manager' => ['*'],
            'admin-menu' => ['*'],
            'admin-menu-type' => ['*'],
            'admin-widget' => ['*']
        ],
        '*' => [
            'user' => ['index'],
            'error' => ['*'],
            'index' => ['*'],
            'content' => ['*']
        ]
    ]
];