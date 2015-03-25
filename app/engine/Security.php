<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Acl;

class Security extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {

        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = 'Users';
        }

        //Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        //Obtain the ACL list
        $acl = $this->getDi()->get('acl');

        //Check if the Role have access to the controller (resource)
        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {

            //If he doesn't have access forward him to the index controller
            $this->flash->error("You don't have access to this module");
            $dispatcher->forward(
                array(
                    'controller' => 'index',
                    'action' => 'index'
                )
            );

            //Returning "false" we tell to the dispatcher to stop the current operation
            return false;
        }

    }

}
