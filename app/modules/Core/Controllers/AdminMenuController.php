<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use DataTables\DataTable;
use Module\Core\Forms\AdminMenuEditForm;
use Module\Core\Models\MenuType;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\EagerLoading\Loader;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Engine\Mvc\AdminController;
use Module\Core\Models\Menu;

/**
 * Class AdminMenuController
 * @package Engine\ModuleCore\Controllers
 */
class AdminMenuController extends AdminController
{
    /**
     * Index action
     * @param null $id
     */
    public function indexAction($id = null)
    {
//        $this->assets->collection('footer-js')->addJs('vendor/jquery-ui/extra/jquery.mjs.nestedSortable.js');
        $this->setTitle('Menu');

        $menuId = $this->request->isPost() ? $this->request->getPost('menuType') : $id;
        if ($menuId == null) { $menuId = 1; }

        $menuType = MenuType::find(['columns' => ['id', 'title']]);

//        $model = Loader::fromResultset(Menu::find([
//            'conditions' => 'menu_type_id = ?1',
//            'bind' => [1 => $menuId],
//            'order' => 'lft'
//        ]), 'viewLevel');
//
//        if (count($model) == 0) {
//            $this->flash->notice("The search did not find any menu");
//        }

        $this->tag->setDefault('menuType', $menuId);

        $this->view->setVars([
//            'model' => $model,
            'menuType' => $menuType
        ]);
    }

    /**
     * Searches for menu
     */
    public function searchAction($id = 1)
    {
        $builder = $this->modelsManager->createBuilder()
//            ->columns('m.id, m.title, m.status, m.parent_id, m.level, m.lft, m.rgt, m.view_level')
            ->addFrom(Menu::class, 'm')
            ->where('m.menu_type_id = 0');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
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
        $form = new AdminMenuEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $this->setTitle('Edit Menu Item');
            $menu = Menu::findFirstById($id);

            if (!$menu) {
                $this->flash->error("Menu was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $form->setEntity($menu);
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

        $form = new AdminMenuEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $menu = Menu::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new Menu();
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

        $this->flashSession->success("Menu was updated successfully");

        $this->response->redirect('admin/core/menu/index/' . $menu->getMenuTypeId())->send();
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
        $menu = Menu::findFirstById($id);
        if (!$menu) {
            return;
        }

        if (!$menu->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }

    public function saveTreeAction()
    {
        if (!$_POST || !$this->request->isAjax()) {
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

        $this->returnJSON(['success' => true]);
        return;
    }
}