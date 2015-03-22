<?php

$router = new Phalcon\Mvc\Router();
$router->add('/admin', array(
    'controller' => 'index',
    'action' => 'index'
));
return $router;