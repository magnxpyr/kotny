<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminContentEditForm;
use Module\Core\Models\Category;
use Module\Core\Models\Content;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Module\Core\Models\User;
use Module\Core\Models\ViewLevel;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminContentController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Articles');
    }

    public function searchAction()
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('c.id, c.title, cy.title as category, v.name as viewLevel, c.featured, u.username, c.created_at, c.status, c.hits')
            ->addFrom(Content::class, 'c')
            ->leftJoin(ViewLevel::class, 'c.view_level = v.id', 'v')
            ->leftJoin(Category::class, 'c.category = cy.id', 'cy')
            ->leftJoin(User::class, 'c.created_by = u.id', 'u');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create Article');
        $form = new AdminContentEditForm();
        $this->view->setVar('form', $form);
        $this->view->setVar('model', new Content());
        $this->view->render('admin-content', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a menu
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Article');
        $form = new AdminContentEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Content::findFirstById($id);
            if (!$model) {
                $this->flash->error("Article was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $this->view->setVar('model', $model);
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

        $form = new AdminContentEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = Content::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Content();
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

        $this->flash->success("Article was updated successfully");

        $this->response->redirect($this->url->previousUri(), true)->send();
        return;
    }

    /**
     * Deletes a menu
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $model = Content::findFirstById($id);
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