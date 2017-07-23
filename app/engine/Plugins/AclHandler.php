<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Engine\Acl\Database;
use Engine\Meta;
use Engine\Package\Manager;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Acl;

/**
 * Class Security
 * @package Engine\Plugins
 */
class AclHandler extends Plugin
{
    use Meta;
    
    /**
     * Check if user has access
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($event, $dispatcher)
    {
        // login user, if remember me exist and user is not logged in
        if ($this->auth->hasRememberMe() && !$this->auth->isUserSignedIn()) {
            $this->auth->loginWithRememberMe(false);
        }

//        $acl = new Database();
//        $acl->addResource('*', '*');
//        $acl->allow('admin', '*', '*');
//        die;

//        $manager = new Manager();
//        $manager->installModule('Core');
        
        
        //Check whether the "auth" variable exists in session to define the active role
        $role = $this->auth->getUserRole();

        //Take the active resources from the dispatcher
        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        //Check if the Role have access to the controller (resource)
        $allowed = $this->di->getShared('acl')->isAllowed((string)$role, "module:$module/$controller", $action);

        if ($allowed != Acl::ALLOW) {
            $this->dispatcher->setModuleName('core');
            $this->dispatcher->forward([
                'namespace' => 'Module\Core\Controllers',
                'module' => 'core',
                'controller' => 'error',
                'action' => 'show404'
            ]);
            return $event->isStopped();
        }
    }
}
