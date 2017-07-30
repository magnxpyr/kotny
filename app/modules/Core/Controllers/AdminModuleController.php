<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Meta;
use Module\Core\Forms\AdminModuleEditForm;
use Module\Core\Models\Module;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Mvc\View;

class AdminModuleController extends AdminController
{
    use Meta;

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle("Module");
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $modules = [];
        foreach (array_diff(scandir(MODULES_PATH), ['..', '.']) as $dir) {
            if (is_dir(MODULES_PATH . "/" . $dir)) {
                $model = new Module();
                $model->setName($dir);
                $modules[$dir] = $model->toArray();
            }
        }

        $model = Module::find()->toArray();

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
        $this->setTitle('Edit Module');
        $form = new AdminModuleEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = Module::findFirstById($id);
            if (!$model) {
                $this->flash->error("Module was not found");

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

        $form = new AdminModuleEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Module::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Module();
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

        $this->cache->delete(Module::getCacheActiveModules());

        $this->flash->success("Module was updated successfully");

        $this->response->redirect('admin/core/module/index')->send();
        return;
    }

    /**
     * Ajax action that deletes a module
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $model = Module::findFirstById($id);
        if (!$model) {
            return;
        }

        if (!$model->delete()) {
            return;
        }

        $this->cache->delete(Module::getCacheActiveModules());

        $this->returnJSON(['success' => true]);
        return;
    }
}