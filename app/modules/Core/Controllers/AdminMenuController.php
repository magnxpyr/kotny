<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Phalcon\Mvc\Model\Criteria;
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

            $this->dispatcher->forward([
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
        $this->setTitle('Create Menu');
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
                $this->flash->error("menu was not found");

                $this->dispatcher->forward([
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
            $this->tag->setDefault("parent_id", $menu->getParentId());
            $this->tag->setDefault("level", $menu->getLevel());
            $this->tag->setDefault("lft", $menu->getLft());
            $this->tag->setDefault("rgt", $menu->getRgt());
            $this->tag->setDefault("role_id", $menu->getRoleId());

        }
    }

    /**
     * Creates a new menu
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $menu = new Menu();
        $menu->setMenuTypeId($this->request->getPost("menu_type_id", "string"));
        $menu->setType($this->request->getPost("type", "string"));
        $menu->setTitle($this->request->getPost("title", "string"));
        $menu->setPath($this->request->getPost("path", "string"));
        $menu->setLink($this->request->getPost("link", "string"));
        $menu->setStatus($this->request->getPost("status", "string"));
        $menu->setParentId($this->request->getPost("parent_id", "string"));
        $menu->setLevel($this->request->getPost("level", "string"));
        $menu->setLft($this->request->getPost("lft", "string"));
        $menu->setRgt($this->request->getPost("rgt", "string"));
        $menu->setRoleId($this->request->getPost("role_id", "string"));

        if (!$menu->save()) {
           $this->flashErrors($menu);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
        }

        $this->flash->success("Menu item was created successfully");

        $this->dispatcher->forward([
            "action" => "index"
        ]);
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
        }

        $id = $this->request->getPost("id");

        $menu = Menu::findFirstById($id);
        if (!$menu) {
            $this->flash->error("Menu item does not exist " . $id);

            $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $menu->setMenuTypeId($this->request->getPost("menu_type_id", "string"));
        $menu->setType($this->request->getPost("type", "string"));
        $menu->setTitle($this->request->getPost("title", "string"));
        $menu->setPath($this->request->getPost("path", "string"));
        $menu->setLink($this->request->getPost("link", "string"));
        $menu->setStatus($this->request->getPost("status", "string"));
        $menu->setParentId($this->request->getPost("parent_id", "string"));
        $menu->setLevel($this->request->getPost("level", "string"));
        $menu->setLft($this->request->getPost("lft", "string"));
        $menu->setRgt($this->request->getPost("rgt", "string"));
        $menu->setRoleId($this->request->getPost("role_id", "string"));


        if (!$menu->save()) {
            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$menu->id]
            ]);
        }

        $this->flash->success("menu was updated successfully");

        $this->dispatcher->forward([
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
        $menu = Menu::findFirstById($id);
        if (!$menu) {
            $this->flash->error("menu was not found");

            $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        if (!$menu->delete()) {
            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                "action" => "search"
            ]);
        }

        $this->flash->success("menu was deleted successfully");

        $this->dispatcher->forward([
                "action" => "index"
        ]);
    }
}