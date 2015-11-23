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
     * @inheritdoc
     */
    public function behaviors() {}

    /**
     * Index action
     * @param null $id
     */
    public function indexAction($id = null)
    {
        $this->assets->collection('footer-js')->addJs('vendor/jquery-ui/extra/jquery.mjs.nestedSortable.js');
        $this->setTitle('Menu');

        $menuId = $this->request->isPost() ? $this->request->getPost('menuType') : $id;
        if ($menuId == null) { $menuId = 1; }

        $menuType = MenuType::find(['columns' => ['id', 'title']]);

        $menu = Menu::find([
            'conditions' => 'menu_type_id = ?1',
            'bind' => [1 => $menuId],
            'order' => 'lft'
        ]);

        if (count($menu) == 0) {
            $this->flash->notice("The search did not find any menu");
        }

        $this->tag->setDefault('menuType', $menuId);

        $this->view->setVars([
            'menu' => $menu,
            'menuType' => $menuType
        ]);
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
            $this->setTitle('Edit Menu Item');
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
            $this->tag->setDefault("view_level", $menu->getViewLevel());
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

        return $this->response->redirect('admin/core/menu/index/' . $menu->getMenuTypeId())->send();
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
        $menuType = Menu::findFirstById($id);
        if (!$menuType) {
            return;
        }

        if (!$menuType->delete()) {
            return;
        }

        return $this->returnJSON(['success' => true]);
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