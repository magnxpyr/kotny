<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Tools\Controllers;

use Module\Tools\Builder\Controller;
use Phalcon\Tag;
use Module\Tools\Builder\View;
use Module\Tools\Helpers\Tools;

/**
 * Class ControllersController
 * @package Tools\Controllers
 */
class AdminControllersController extends ControllerBase
{
    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        $this->setTitle("Create Controller");
        $selectedModule = null;
        $params = $this->router->getParams();
        if(!empty($params))
            $selectedModule = $this->router->getParams()[0];
        $this->view->selectedModule = $selectedModule;
        $this->view->directoryPath = Tools::getModulesPath() . $selectedModule . Tools::getControllersDir();
    }

    /**
     * Generate controller
     */
    public function createAction()
    {
        if ($this->request->isPost()) {

            $controllerName = $this->request->getPost('name', 'string');
            $directory = $this->request->getPost('directory', 'string');
            $moduleName = $this->request->getPost('package', 'string');
            $namespace = $this->request->getPost('namespace', 'string');
            $baseClass = $this->request->getPost('baseClass', 'string');
            $force = $this->request->getPost('force', 'int');
            $view = $this->request->getPost('view', 'int');

            try {
                $controllerBuilder = new Controller(array(
                    'name' => $controllerName,
                    'module' => $moduleName,
                    'directory' => $directory,
                    'namespace' => $namespace,
                    'baseClass' => $baseClass,
                    'force' => $force
                ));

                $controllerFileName = $controllerBuilder->build();

                if(!empty($view)) {
                    $viewBuilder = new View(array(
                        'name' => $controllerName,
                        'module' => $moduleName,
                        'force' => $force
                    ));

                    $viewBuilder->build();
                }

                $this->flash->success('The controller "'.$controllerFileName.'" was created successfully');
                if(!empty($view)) {
                    $this->flash->success('The view for controller "' . $controllerFileName . '" was created successfully');
                }
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
