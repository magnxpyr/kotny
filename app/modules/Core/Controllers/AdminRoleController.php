<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminRoleEditForm;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;
use Module\Core\Models\Role;

/**
 * Class AdminRoleController
 * @package Module\Core\Controllers
 */
class AdminRoleController extends AdminController
{
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
                ->from('Module\Core\Models\Role');

            $dataTables = new DataTable();
            $dataTables->fromBuilder($builder)->sendResponse();
        }
    }

    /**
     * Displays the creation form
     */
    public function createAction()
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
            $form->setEntity($model);
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
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = Role::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Role();
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
