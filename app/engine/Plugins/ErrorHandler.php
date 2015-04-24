<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin {

    // This action is executed before execute any action in the application
    public function beforeException($event, $dispatcher, $exception) {
        if ($exception instanceof Exception) {
            $dispatcher->forward(
                [
                    'module' => 'core',
                    'namespace' => 'Core\Controllers',
                    'controller' => 'error',
                    'action' => 'show404'
                ]
            );
            return false;
        }

        if ($this->getDI()->getShared('config')->app->development) {
            throw $exception;
        } else {
        //    EngineException::logException($exception);
        }

        // Handle other exceptions.
        $dispatcher->forward(
            [
                'module' => 'core',
                'namespace' => 'Core\Controllers',
                'controller' => 'error',
                'action' => 'show500'
            ]
        );
        return $event->isStopped();
    }
}