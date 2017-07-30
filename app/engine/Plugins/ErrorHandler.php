<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Engine\Meta;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\User\Plugin;

/**
 * Class ErrorHandler
 * @package Engine\Plugins
 */
class ErrorHandler extends Plugin
{
    use Meta;

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
    public function beforeException($event, $dispatcher, $exception)
    {
        if (DEV) {
            throw $exception;
        } else {
            $this->logger->error('Page error: ', $exception);
        }

        if ($exception instanceof Exception) {
            $dispatcher->forward(
                [
                    'module' => 'core',
                    'namespace' => 'Module\Core\Controllers',
                    'controller' => 'error',
                    'action' => 'show404'
                ]
            );
            return false;
        }

        // Handle other exceptions.
        $dispatcher->forward(
            [
                'module' => 'core',
                'namespace' => 'Module\Core\Controllers',
                'controller' => 'error',
                'action' => 'show503'
            ]
        );
        return $event->isStopped();
    }
}