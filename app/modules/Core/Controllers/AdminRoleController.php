<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminRoleEditForm;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;
use Core\Models\Role;

/**
 * Class AdminRoleController
 * @package Core\Controllers
 */
class AdminRoleController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {}

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Roles');
    }

    public function searchAction()
    {
        if ($this->request->isAjax()) {
            $builder = $this->modelsManager->createBuilder()
                ->from('Core\Models\Role');

            $dataTables = new DataTable();
            $dataTables->fromBuilder($builder)->sendResponse();
        }
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->setTitle('Create Role');
        $form = new AdminRoleEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-role', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a role
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Role');
        $form = new AdminRoleEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Role::findFirstById($id);
            if (!$model) {
                $this->flash->error("Role was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $this->tag->setDefault("id", $model->getId());
            $this->tag->setDefault("name", $model->getName());
            $this->tag->setDefault("parent_id", $model->getParentId());
            $this->tag->setDefault("description", $model->getDescription());
        }
    }

    /**
     * Saves a role
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminRoleEditForm();
        if (!empty($this->request->getPost('id'))) {
            $menu = Role::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Role();
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

        $this->flash->success("Role was updated successfully");

        $this->response->redirect('admin/core/role/index')->send();
        return;
    }

    /**
     * Deletes a role
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = Role::findFirstById($id);
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
