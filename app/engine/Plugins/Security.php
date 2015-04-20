<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Phalcon\Mvc\User\Plugin,
    Phalcon\Acl;

class Security extends Plugin
{
    public function beforeExecuteRoute($event, $dispatcher)
    {
        //Obtain the ACL list
        $acl = $this->di['acl'];

        //By default the action is deny access
        $acl->setDefaultAction(Acl::ALLOW);

        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if ($auth) {
            $role = 'Admin';
            // Give Admins full access without checking
            $acl->setDefaultAction(Acl::ALLOW);
        } else {
            $role = 'Guest';

            //Take the active controller/action from the dispatcher
            $module = $dispatcher->getModuleName();
            $controller = $dispatcher->getControllerName();
            $action = $dispatcher->getActionName();
            //     echo $role . ' ' . $module .' '.$controller .' ' .$action;


            //Check if the Role have access to the controller (resource)
            $allowed = $acl->isAllowed($role, $module . '_' . $controller, $action);
            //   echo $allowed .' ' . Acl::ALLOW;
            if ($allowed != Acl::ALLOW) {

                //If he doesn't have access forward him to the index controller
                $this->flash->error("You don't have access to this module");

                $this->di['response']->redirect('error/show404', false, 301);

                //Returning "false" we tell to the dispatcher to stop the current operation
                return false;
            }
        }

    }
}
