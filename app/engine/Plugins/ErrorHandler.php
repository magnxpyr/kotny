<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine\Plugins;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin {

    // This action is executed before execute any action in the application
    public function beforeException($event, $dispatcher, $exception) {
        /*
        if ($exception instanceof Exception) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $this->di['response']->redirect('error/show404', false, 301);
                    return false;
            }
        }

        $this->di['response']->redirect('error/show500', false, 301);

        return false;
        */
    }
}