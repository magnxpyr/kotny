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
            ->from('Core\Models\ViewLevel');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
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
            $this->tag->setDefault("name", $model->getName());
            if (!empty($model->getRoles())) {
                foreach ($model->getRoles() as $role) {
                    $this->tag->setDefault("role$role", $role);
                }
            }
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
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = ViewLevel::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new ViewLevel();
        }

        $roles = [];
        $post = $this->request->getPost();
        foreach ($post['role'] as $role) {
            $post["role$role"] = $role;
            $roles[] = (int)$role;
        }
        unset($post['role']);
        $form->bind($post, $menu);

        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        $menu->setRoles(json_encode($roles));

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
