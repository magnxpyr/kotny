<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Phalcon\Mvc\User\Plugin,
    Phalcon\Acl;

/**
 * Class Security
 * @package Engine\Plugins
 */
class AclHandler extends Plugin
{
    /**
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($event, $dispatcher)
    {
        //By default the action is deny access
        $this->acl->setDefaultAction(Acl::ALLOW);

        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if ($auth) {
            $role = 'Admin';
            // Give Admins full access without checking
            $this->acl->setDefaultAction(Acl::ALLOW);
        } else {
            $role = 'Guest';

            //Take the active controller/action from the dispatcher
            $module = $dispatcher->getModuleName();
            $controller = $dispatcher->getControllerName();
            $action = $dispatcher->getActionName();
            //     echo $role . ' ' . $module .' '.$controller .' ' .$action;


            //Check if the Role have access to the controller (resource)
            $allowed = $this->acl->isAllowed($role, $module . '_' . $controller, $action);
            //   echo $allowed .' ' . Acl::ALLOW;
            if ($allowed != Acl::ALLOW) {

                //If he doesn't have access forward him to the index controller
                $this->flash->error("You don't have access to this module");

                $this->response->setStatusCode(404, 'Page Not Found');

                //Returning "false" we tell to the dispatcher to stop the current operation
                return false;
            }
        }
    }
}
