<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminMenuTypeEditForm;
use Phalcon\Mvc\Model\Criteria;
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

                return $this->dispatcher->forward([
                    "action" => "index"
                ]);
            }

            $this->tag->setDefault("id", $menuType->getId());
            $this->tag->setDefault("title", $menuType->getTitle());
            $this->tag->setDefault("role_id", $menuType->getRoleId());
            $this->tag->setDefault("description", $menuType->getDescription());
        }
    }

    /**
     * Saves a menu edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $form = new AdminMenuTypeEditForm();
        $menuType = new MenuType();
        $form->bind($this->request->getPost(), $menuType);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            return $this->dispatcher->forward([
                "action" => "new"
            ]);
        }

        if (!$menuType->save()) {
            $this->flashErrors($menuType);

            return $this->dispatcher->forward([
                "action" => "new"
            ]);
        }

        $this->flash->success("Menu was updated successfully");

        return $this->dispatcher->forward([
            "action" => "index"
        ]);
    }

    /**
     * Deletes a menu
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $menuType = MenuType::findFirstById($id);
        if (!$menuType) {
            $this->flash->error("Menu was not found");

            return $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        if (!$menuType->delete()) {
            $this->flashErrors($menuType);

            return $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $this->flash->success("Menu was deleted successfully");

        return $this->dispatcher->forward([
            "action" => "index"
        ]);
    }
}