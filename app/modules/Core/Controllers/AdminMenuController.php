<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\AdminMenuEditForm;
use Core\Models\MenuType;
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
    public function indexAction($id = null)
    {
        $this->assets->collection('footer-js')->addJs('vendor/jquery-ui/extra/jquery.mjs.nestedSortable.js');
        $this->setTitle('Menu');

        $menu_id =  $this->request->isPost() ? $this->request->getPost('menu_id') : $id;

        $menu = Menu::find([
            'conditions' => 'menu_type_id = ?1',
            'bind' => [1 => $menu_id],
            'order' => 'lft'
        ]);

        if (count($menu) == 0) {
            $this->flash->notice("The search did not find any menu");
        }

        $this->view->setVar('menu', $menu);
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
        if ($this->request->has('menu_type')) {
            $this->tag->setDefault("menu_type_id", $this->request->get('menu_type_id'));
        }
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

            $form = new AdminMenuEditForm();
            $this->view->setVar('form', $form);

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
        if (!empty($this->request->getPost('id'))) {
            $menu = Menu::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Menu();
        }

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

    public function saveTreeAction()
    {
        if (!$this->request->getPost() || !$this->request->isAjax()) {
            return;
        }

        $data = $this->request->getPost('data');

        foreach ($data as $el) {
            if ($el['item_id']) {
                $model = Menu::findFirstById($el['item_id']);
                if ($model) {
                    if ($el['parent_id']) {
                        $model->setParentId($el['parent_id']);
                    } else {
                        $model->setParentId(0);
                    }
                    $model->setLevel($el['depth']);
                    $model->setLft($el['left']);
                    $model->setRgt($el['right']);
                    $model->update();
                }
            }
        }

        return $this->returnJSON(['success' => true]);
    }
}