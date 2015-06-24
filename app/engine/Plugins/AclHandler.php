<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Core\Models\User;
use Phalcon\Mvc\User\Plugin,
    Phalcon\Acl;

/**
 * Class Security
 * @package Engine\Plugins
 */
class AclHandler extends Plugin
{
    /**
     * Check if user has access
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($event, $dispatcher)
    {
        //By default the action is deny access
        $this->acl->setDefaultAction(Acl::DENY);

        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if ($auth) {
            $role = User::getRoleById($auth['id']);
        } else {
            $role = 1;
        }

        //Take the active resources from the dispatcher
        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        //Check if the Role have access to the controller (resource)
        $allowed = $this->acl->isAllowed($role, $module . '/' . $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $this->dispatcher->forward([
                'namespace' => 'Core\Controllers',
                'module' => 'core',
                'controller' => 'error',
                'action' => 'show404'
            ]);
        }
    }
}
