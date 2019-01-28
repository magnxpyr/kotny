<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminAliasEditForm;
use DataTables\DataTable;
use Module\Core\Models\Alias;
use Engine\Mvc\AdminController;
use Module\Core\Models\Route;
use Phalcon\Mvc\View;

/**
 * Class AdminAliasController
 * @package Module\Core\Controllers
 */
class AdminAliasController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Custom urls');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->columns('a.id, a.url, r.name as route_name, a.status')
            ->addFrom('Module\Core\Models\Alias', 'a')
            ->leftJoin('Module\Core\Models\Route', 'a.route_id = r.id', 'r');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create url');
        $form = new AdminAliasEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-alias', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits an alias
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit url');
        $form = new AdminAliasEditForm();
        $this->view->setVars([
            'form' => $form,
            'params' => null
        ]);
        if (!$this->request->isPost()) {
            $model = Alias::findFirstById($id);
            if (!$model) {
                $this->flash->error("Url was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
            $this->view->setVar('params', $this->renderRouteParams($model->getParams()));
        }
    }

    /**
     * Saves an alias
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminAliasEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = Alias::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Alias();
        }

        $form->bind($_POST, $menu);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        if (!$menu->save()) {
            $this->flashErrors($menu);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        $this->flash->success("Url was updated successfully");

        $this->response->redirect('admin/core/alias/index')->send();
        return;
    }

    public function routeParamsAction()
    {
        if (!$this->request->isAjax() || !$this->request->isGet()) {
            return;
        }
        /** @var Route $model */
        $route = Route::findFirstById($_GET['routeId']);
        if (!$route) {
            return $this->returnJSON(['success' => false]);
        }

        $aliasParams = null;

        if (isset($_GET['aliasId'])) {
            $alias = Alias::findFirstById($_GET['aliasId']);
            if ($alias) {
                $aliasParams = $alias->getParamsArray();
            }
        }

        $paramsModel = $route->getParamsArray();
        $params = [];
        if ($paramsModel != null) {
            foreach ($paramsModel as $key => $value) {
                if ($aliasParams != null && isset($aliasParams->$key)) {
                    $params[$key] = $value;
                } else {
                    $params[$key] = "";
                }
            }
        }

        return $this->returnJSON([
            'html' => $this->renderRouteParams($params),
            'success' => true
        ]);
    }

    /**
     * Deletes an alias
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = Alias::findFirstById($id);
        if (!$menuType) {
            $this->returnJSON(['success' => false]);
            return;
        }

        if (!$menuType->delete()) {
            $this->returnJSON(['success' => false]);
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }

    private function renderRouteParams($params)
    {
        return $this->viewSimple->render(MODULES_PATH . 'Core/Views/admin-alias/edit-route-params', ['params' => $params]);
    }
}
