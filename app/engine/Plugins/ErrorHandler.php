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

    /**
     * This action is executed before execute any action in the application
     * If a page is not found throws an error
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @param \Phalcon\Mvc\Dispatcher\Exception $exception
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     * @return \Phalcon\Mvc\View
     */
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
        //    log the exception
        }

        // Handle other exceptions.
        $dispatcher->forward(
            [
                'module' => 'core',
                'namespace' => 'Core\Controllers',
                'controller' => 'error',
                'action' => 'show503'
            ]
        );
        return $event->isStopped();
    }
}