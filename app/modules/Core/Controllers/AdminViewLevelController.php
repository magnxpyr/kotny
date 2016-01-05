<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminViewLevelEditForm;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Core\Models\ViewLevel;
use Phalcon\Mvc\View;

/**
 * Class AdminViewLevelController
 * @package Core\Controllers
 */
class AdminViewLevelController extends AdminController
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
        $this->setTitle('View Levels');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->columns('u.id, u.username, u.email, r.name, u.status')
            ->addFrom('Core\Models\User', 'u')
            ->addFrom('Core\Models\Role', 'r')
            ->where('u.role_id = r.id');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->setTitle('Create View Level');
        $form = new AdminViewLevelEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-view-level', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a view level
     *
     * @param string $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit View Level');
        $form = new AdminViewLevelEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = ViewLevel::findFirstById($id);
            if (!$model) {
                $this->flash->error("View level was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $this->tag->setDefault("id", $model->getId());
            $this->tag->setDefault("username", $model->getUsername());
            $this->tag->setDefault("password", $model->getPassword());
            $this->tag->setDefault("email", $model->getEmail());
            $this->tag->setDefault("role_id", $model->getRoleId());
            $this->tag->setDefault("status", $model->getStatus());
        }
    }

    /**
     * Saves a view level
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminViewLevelEditForm();
        if (!empty($this->request->getPost('id'))) {
            $menu = ViewLevel::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new ViewLevel();
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

        $this->flash->success("View level was updated successfully");

        $this->response->redirect('admin/core/view-level/index')->send();
        return;
    }

    /**
     * Deletes a view level
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = ViewLevel::findFirstById($id);
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
