<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminMenuTypeEditForm;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Engine\Mvc\AdminController;
use Core\Models\MenuType;

/**
 * Class AdminMenuController
 * @package Engine\ModuleCore\Controllers
 */
class AdminMenuTypeController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Menu Type');

        $numberPage = 1;

        $model = MenuType::find();

        if (count($model) == 0) {
            $this->flash->notice("The search did not find any menu");
        }

        $paginator = new Paginator([
            "data" => $model,
            "limit"=> 10,
            "page" => $numberPage
        ]);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create Menu');
        $form = new AdminMenuTypeEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-menu-type', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a menu
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Menu');
        $form = new AdminMenuTypeEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $menuType = MenuType::findFirstById($id);
            if (!$menuType) {
                $this->flash->error("Menu was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $this->tag->setDefault("id", $menuType->getId());
            $this->tag->setDefault("title", $menuType->getTitle());
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

        $form = new AdminMenuTypeEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = MenuType::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new MenuType();
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

        $this->flash->success("Menu was updated successfully");

        $this->response->redirect('admin/core/menu-type/index')->send();
        return;
    }

    /**
     * Deletes a menu
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = MenuType::findFirstById($id);
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