<?php

return [
    'copyright' => "/**\n* @copyright   2006 - 2015 Magnxpyr Network\n".
        "* @license     New BSD License; see LICENSE\n".
        "* @link        http://www.magnxpyr.com\n".
        "* @author      Stefan Chiriac <stefan@magnxpyr.com>\n*/",
    'modulesPath' => APP_PATH . 'modules/',
    'migrationsPath' => APP_PATH . 'migrations/',
 //   'viewsDir' => '',
//    'modulesDir' => '',
//    'controllersDir' => '',
//    'formsDir' => '',
//    'allow' => '',
    'baseController' => ['Engine\Controller', 'Engine\AdminController'],
    'baseModel' => [],
    'baseForm' => [],
    'baseModule' => 'Engine\Module',
 //   'baseRoute' => ''
    'full' => false,
    'advanced' => true
];