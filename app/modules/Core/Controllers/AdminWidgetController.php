<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Engine\Mvc\AdminController;

class AdminWidgetController extends AdminController
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
        $this->setTitle('Menu Type');

        $numberPage = 1;

        $menuType = MenuType::find();

        if (count($menuType) == 0) {
            $this->flash->notice("The search did not find any menu");
        }

        $paginator = new Paginator([
            "data" => $menuType,
            "limit"=> 10,
            "page" => $numberPage
        ]);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    /**
     * Displays the creation form
     */
    public function newAction()
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
        if (!empty($this->request->getPost('id'))) {
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