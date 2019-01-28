<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminUserEditForm;
use DataTables\DataTable;
use Module\Core\Models\User;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Pager;

class AdminUserController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Users');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->columns('u.id, u.username, u.email, r.name, u.status')
            ->addFrom('Module\Core\Models\User', 'u')
            ->addFrom('Module\Core\Models\Role', 'r')
            ->where('u.role_id = r.id');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create User');
        $form = new AdminUserEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-user', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit User');
        $form = new AdminUserEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = User::findFirstById($id);
            if (!$model) {
                $this->flash->error("User was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
        }
    }

    /**
     * Saves a user
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminUserEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = User::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new User();
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

        if (!$this->security->checkHash($_POST['password'], $menu->getPassword())) {
            $menu->setPassword($this->security->hash($_POST['password']));
        }

        if (!$menu->save()) {
            $this->flashErrors($menu);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        $this->flash->success("Menu was updated successfully");

        $this->response->redirect('admin/core/user/index')->send();
        return;
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = User::findFirstById($id);
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
