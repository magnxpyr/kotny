<?php

return [
    'copyright' => "/**\n * @copyright   2006 - 2016 Magnxpyr Network\n".
        " * @license     New BSD License; see LICENSE\n".
        " * @link        http://www.magnxpyr.com\n".
        " * @author      Stefan Chiriac <stefan@magnxpyr.com>\n */",
    'modulesPath' => APP_PATH . 'modules/',
    'migrationsPath' => APP_PATH . 'migrations/',
    'baseController' => ['Engine\Mvc\Controller', 'Engine\Mvc\AdminController'],
    'baseModule' => 'Engine\Mvc\Module',
    'full' => false
];