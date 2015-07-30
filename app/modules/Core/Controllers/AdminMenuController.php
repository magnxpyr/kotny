<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminMenuEditForm;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Engine\Mvc\AdminController;
use Core\Models\Menu;

/**
 * Class AdminMenuController
 * @package Engine\ModuleCore\Controllers
 */
class AdminMenuController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Menu');

        $numberPage = 1;

        $menu = Menu::find();

        if (count($menu) == 0) {
            $this->flash->notice("The search did not find any menu");
        }

        $paginator = new Paginator([
            "data" => $menu,
            "limit"=> 10,
            "page" => $numberPage
        ]);

        $this->view->setVar('page', $paginator->getPaginate());

    }

    /**
     * Searches for menu
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Core\\Models\\Menu", $_POST);
            $this->persistent->set('parameters', $query->getParams());
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->get('parameters');
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $menu = Menu::find($parameters);
        if (count($menu) == 0) {
            $this->flash->notice("The search did not find any menu");

            return $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $paginator = new Paginator([
            "data" => $menu,
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
        $this->setTitle('New Menu Item');
        $form = new AdminMenuEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-menu', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a menu
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $menu = Menu::findFirstById($id);
            if (!$menu) {
                $this->flash->error("Menu was not found");

                return $this->dispatcher->forward([
                    "action" => "index"
                ]);
            }
            $this->view->id = $menu->id;

            $this->tag->setDefault("id", $menu->getId());
            $this->tag->setDefault("menu_type_id", $menu->getMenuTypeId());
            $this->tag->setDefault("type", $menu->getType());
            $this->tag->setDefault("title", $menu->getTitle());
            $this->tag->setDefault("path", $menu->getPath());
            $this->tag->setDefault("link", $menu->getLink());
            $this->tag->setDefault("status", $menu->getStatus());
            $this->tag->setDefault("role_id", $menu->getRoleId());

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

        $form = new AdminMenuEditForm();
        $menu = new Menu();
        $form->bind($this->request->getPost(), $menu);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            return $this->dispatcher->forward([
                "action" => "new"
            ]);
        }

        if (!$menu->save()) {
            $this->flashErrors($menu);

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
        $menuType = Menu::findFirstById($id);
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