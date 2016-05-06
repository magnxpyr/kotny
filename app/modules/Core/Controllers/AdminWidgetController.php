<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
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
            $menuType = Widget::findFirstById($id);
            if (!$menuType) {
                $this->flash->error("Menu was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $this->tag->setDefault("id", $menuType->getId());
            $this->tag->setDefault("status", $menuType->getStatus());
            $this->tag->setDefault("description", $menuType->getDescription());
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
            $menu = Widget::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Widget();
        }

        $form->bind($this->request->getPost(), $menu);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        if (!$menu->save()) {
            $this->flashErrors($menu);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        $this->flash->success("Widget was updated successfully");

        $this->response->redirect('admin/core/widget/index')->send();
        return;
    }

    /**
     * Deletes a widget
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = Widget::findFirstById($id);
        if (!$menuType) {
            return;
        }

        if (!$menuType->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }
}