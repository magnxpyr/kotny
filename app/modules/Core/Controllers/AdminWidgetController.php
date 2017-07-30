<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminWidgetEditForm;
use Module\Core\Models\Widget;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AdminWidgetController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Widget');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $modules = [];
        foreach (array_diff(scandir(WIDGETS_PATH), ['..', '.']) as $dir) {
            if (is_dir(WIDGETS_PATH . "/" . $dir)) {
                $model = new Widget();
                $model->setName($dir);
                $modules[$dir] = $model->toArray();
            }
        }

        $model = Widget::find()->toArray();

        foreach ($model as $item) {
            if (isset($modules[$item['name']])) {
                $modules[$item['name']] = $item;
            }
        }

        $dataTables = new DataTable();
        $dataTables->fromArray($modules)->sendResponse();
    }

    /**
     * Edits a menu
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Menu');
        $form = new AdminWidgetEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Widget::findFirstById($id);
            if (!$model) {
                $this->flash->error("Widget was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
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

        $form = new AdminWidgetEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Widget::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Widget();
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

        $this->cache->delete(Widget::getCacheActiveWidgets());

        $this->flash->success("Widget was updated successfully");

        $this->response->redirect('admin/core/widget/index')->send();
        return;
    }
}