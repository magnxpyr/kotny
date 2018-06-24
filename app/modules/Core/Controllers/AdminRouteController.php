<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminRouteEditForm;
use DataTables\DataTable;
use Module\Core\Models\Route;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;

/**
 * Class AdminRouteController
 * @package Module\Core\Controllers
 */
class AdminRouteController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Routes');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->columns('r.id, r.name, r.pattern, r.status, r.ordering')
            ->addFrom('Module\Core\Models\Route', 'r');
        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create Route');
        $form = new AdminRouteEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-route', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a route
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Route');
        $form = new AdminRouteEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Route::findFirstById($id);
            if (!$model) {
                $this->flash->error("Route was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
        }
    }

    /**
     * Saves a route
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminRouteEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Route::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Route();
        }

        $form->bind($_POST, $model);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        if (!$model->save()) {
            $this->flashErrors($model);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        $this->flash->success("Route was updated successfully");

        $this->cache->delete(Route::getCacheActiveRoutes());

        $this->response->redirect('admin/core/route/index')->send();
        return;
    }

    public function updateOrderingAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            $this->returnJSON(['success' => false]);
            return;
        }

        $data = $this->request->getPost('data');

        $this->db->begin();

        foreach ($data as $d) {
            $query = "UPDATE " . Route::class . " SET ordering = :ordering: WHERE id = :id:";
            $this->modelsManager->executeQuery($query, [
                'id' => $d['id'],
                'ordering' => $d['ordering']
            ]);
        }

        $this->db->commit();

        $this->cache->delete(Route::getCacheActiveRoutes());

        $this->returnJSON(['success' => true]);
        return;
    }

    /**
     * Deletes a route
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            $this->returnJSON(['success' => false]);
            return;
        }

        $model = Route::findFirstById($id);
        if (!$model) {
            $this->returnJSON(['success' => false]);
            return;
        }

        if (!$model->delete()) {
            $this->returnJSON(['success' => false]);
            return;
        }

        $this->cache->delete(Route::getCacheActiveRoutes());

        $this->returnJSON(['success' => true]);
        return;
    }
}
