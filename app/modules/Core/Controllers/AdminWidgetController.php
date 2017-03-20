<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminWidgetEditForm;
use Core\Models\Widget;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminWidgetController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Widget');

        $numberPage = 1;

        $model = Widget::find();

        if (count($model) == 0) {
            $this->flash->notice("The search did not find any widget");
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
            ->from('Core\Models\Widget');

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
        $this->setTitle('Edit Menu');
        $form = new AdminWidgetEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Widget::findFirstById($id);
            if (!$model) {
                $this->flash->error("Widget was not found");

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

        $form = new AdminWidgetEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Widget::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Widget();
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

        $this->flash->success("Widget was updated successfully");

        $this->response->redirect('admin/core/widget/index')->send();
        return;
    }

    /**
     * Ajax action that deletes a widget
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $modelType = Widget::findFirstById($id);
        if (!$modelType) {
            return;
        }

        if (!$modelType->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }
}