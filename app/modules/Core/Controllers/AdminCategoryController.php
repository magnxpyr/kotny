<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminCategoryEditForm;
use Core\Models\Category;
use Engine\Mvc\AdminController;
use Engine\Utils;
use Phalcon\Mvc\Model\EagerLoading\Loader;
use Phalcon\Mvc\View;

class AdminCategoryController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->assets->collection('footer-js')->addJs('vendor/jquery-ui/extra/jquery.mjs.nestedSortable.js');
        $this->setTitle('Categories');

        $model = Loader::fromResultset(Category::find([
            'order' => 'lft'
        ]), 'viewLevel');

        if (count($model) == 0) {
            $this->flash->notice("The search did not find any categories");
            $model = [];
        }

        $this->view->setVars([
            'model' => $model
        ]);
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('New Category');
        $form = new AdminCategoryEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-category', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a category
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $this->setTitle('Edit Category');
            $category = Category::findFirstById($id);

            if (!$category) {
                $this->flash->error("Category was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $form = new AdminCategoryEditForm();
            $this->view->setVar('form', $form);

            $this->tag->setDefault("id", $category->getId());
            $this->tag->setDefault("title", $category->getTitle());
            $this->tag->setDefault("alias", $category->getAlias());
            $this->tag->setDefault("status", $category->getStatus());
            $this->tag->setDefault("view_level", $category->getViewLevel());
            $this->tag->setDefault("metadata", $category->getMetadata());
            $this->tag->setDefault("description", $category->getDescription());
        }
    }

    /**
     * Saves a category edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminCategoryEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Category::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Category();
        }

        $form->bind($this->request->getPost(), $model);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        if (!$model->save()) {
            $this->flashErrors($model);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        $this->flash->success("Category was updated successfully");

        $this->response->redirect('admin/core/category/index')->send();
        return;
    }

    /**
     * Deletes a category
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $model = Category::findFirstById($id);
        if (!$model || !$model->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }

    public function saveTreeAction()
    {
        if (!$this->request->getPost() || !$this->request->isAjax()) {
            return;
        }

        $data = $this->request->getPost('data');

        foreach ($data as $el) {
            if ($el['item_id']) {
                $model = Category::findFirstById($el['item_id']);
                if ($model) {
                    if ($el['parent_id']) {
                        $model->setParentId($el['parent_id']);
                    }
                    $model->setLevel($el['depth']);
                    $model->setLft($el['left']);
                    $model->setRgt($el['right']);
                    $model->update();
                }
            }
        }

        $this->returnJSON(['success' => true]);
        return;
    }
}