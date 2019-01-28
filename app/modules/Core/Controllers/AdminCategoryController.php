<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminCategoryEditForm;
use Module\Core\Models\Category;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\Model\EagerLoading\Loader;
use Phalcon\Mvc\View;

class AdminCategoryController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Categories');

        $category = Category::find(['order' => 'lft']);
        $model = Loader::fromResultset($category, 'viewLevel');

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
        $form = new AdminCategoryEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $this->setTitle('Edit Category');
            $model = Category::findFirstById($id);

            if (!$model) {
                $this->flash->error("Category was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
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
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            return;
        }

        $data = $this->request->getPost('data');

        foreach ($data as $el) {
            $id = null;
            if (isset($el['item_id'])) {
                $id = $el['item_id'];
            } elseif (isset($el['id'])) {
                $id = $el['id'];
            }
            if (!empty($id)) {
                $model = Category::findFirstById($id);
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