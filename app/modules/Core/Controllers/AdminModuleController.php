<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminModuleEditForm;
use Core\Models\Module;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminModuleController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Module');

        $numberPage = 1;

        $model = Module::find();

        if (count($model) == 0) {
            $this->flash->notice("The search did not find any module");
        }

        $paginator = new Paginator([
            "data" => $model,
            "limit"=> 10,
            "page" => $numberPage
        ]);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('Core\Models\Module');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Edits a menu
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Module');
        $form = new AdminModuleEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Module::findFirstById($id);
            if (!$model) {
                $this->flash->error("Module was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
        }
    }

    /**
     * Saves a menu edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminModuleEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Module::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Module();
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

        $this->flash->success("Module was updated successfully");

        $this->response->redirect('admin/core/module/index')->send();
        return;
    }

    /**
     * Ajax action that deletes a module
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $model = Module::findFirstById($id);
        if (!$model) {
            return;
        }

        if (!$model->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }
}