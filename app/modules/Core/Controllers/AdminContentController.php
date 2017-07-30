<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminContentEditForm;
use Module\Core\Models\Content;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminContentController extends AdminController
{
    public function initialize()
    {
        parent::initialize();
    }

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
            ->addFrom('Module\Core\Models\Content', 'c')
            ->addFrom('Module\Core\Models\Category', 'cy')
            ->addFrom('Module\Core\Models\User', 'u')
            ->addFrom('Module\Core\Models\ViewLevel', 'v')
            ->where('c.category = cy.id')
            ->andWhere('c.view_level = v.id')
            ->andWhere('c.created_by = u.id');

//        $builder = $this->modelsManager->createQuery("SELECT c.id, c.title, cy.title as category, v.name as viewLevel, c.featured, u.username, c.created_at, c.status, c.hits
//        FROM \Module\Core\Models\Content c, \Module\Core\Models\Category cy, \Module\Core\Models\User u, \Module\Core\Models\ViewLevel v WHERE c.category = cy.id
//        and c.view_level = v.id and c.created_by = u.id")->execute();

        $columns = ['c.id', 'c.title', ['cy.title', 'alias' => 'category'], ['v.name', 'alias' => 'viewLevel'],
            'c.featured', 'u.username', 'c.created_at', 'c.status', 'c.hits'];

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder, $columns)->sendResponse();
//        $dataTables->fromResultSet($builder, $columns)->sendResponse();
//        $dataTables->fromArray($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create Article');
        $form = new AdminContentEditForm();
        $this->view->setVar('form', $form);
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

        $this->response->redirect('admin/core/content/index')->send();
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