<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Tools\Controllers;

use Engine\Package\PackageType;
use Module\Tools\Builder\Module;

/**
 * Class ModulesController
 * @package Tools\Controllers
 */
class AdminModulesController extends ControllerBase
{
    /**
     * Create module form
     */
    public function indexAction()
    {
        $this->setTitle("Create Module");
    }

    /**
     * List Modules Action
     */
    public function listAction()
    {
        $this->setTitle("List Modules");
    }

    /**
     * Create Module Action
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            $name = $this->request->getPost('name');
            $directory = $this->request->getPost('directory');
            $namespace = $this->request->getPost('namespace');
            $routes = $this->request->getPost('routes', 'int');
            $force = $this->request->getPost('force', 'int');

            try {
                $component = array(
                    'name'      => $name,
                    'type'      => PackageType::MODULE,
                    'directory' => $directory,
                    'namespace' => $namespace,
                    'routes'    => $routes,
                    'force'     => $force
                );

                $moduleBuilder = new Module($component);
                $moduleBuilder->build();

                $this->flash->success('Module "'.$name.'" was created successfully');

            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }

        $this->dispatcher->forward(array(
            'action' => 'index'
        ));
        return;
    }
}