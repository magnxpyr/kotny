<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Engine\Mvc\Exception;
use Engine\Package\PackageType;
use Module\Core\Forms\AdminPackageEditForm;
use Module\Core\Forms\AdminPackageManagerForm;
use Module\Core\Models\Package;
use Module\Core\Models\Route;

/**
 * Class AdminPackageManagerController
 * @package Module\Core\Controllers
 */
class AdminPackageManagerController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Package Manager');

        $form = new AdminPackageManagerForm();
        $this->view->setVar('form', $form);
    }

    /**
     * Upload and install a package
     */
    public function uploadAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }
        $this->logger->debug("Uploading package");

        $form = new AdminPackageManagerForm();
        if (!$form->isValid(array_merge($_POST, $_FILES))) {
            $this->logger->error("Form or file type is not valid", $form->getMessages());
            $this->flashErrors($form);
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        if ($this->request->hasFiles(true)) {
            try {
                foreach ($this->request->getUploadedFiles(true) as $file) {
                    if ($file->moveTo(TEMP_PATH . $file->getName())) {
                        $this->packageManager->installPackage($file->getName());
                    }
                }
                $this->flashSession->success("Package installed successfully");
            } catch (Exception $e) {
                $this->flashSession->error($e->getMessage());
            }
        }
        $this->dispatcher->forward([
            "action" => "index"
        ]);
    }

    /**
     * Install a package
     * @param $package string Package name
     * @param $packageType PackageType
     */
    public function installPackageAction($package, $packageType)
    {
        try {
            switch ($packageType) {
                case PackageType::module:
                    $this->packageManager->installModule($package);
                    break;
                case PackageType::widget:
                    $this->packageManager->installWidget($package);
                    break;
            }
            $this->flashSession->success("Package installed successfully");
        } catch (Exception $e) {
            $this->flashSession->error($e->getMessage());
        }

        $this->cache->delete(Route::getCacheActiveRoutes());

        $this->dispatcher->forward([
            "action" => "index"
        ]);
    }

    /**
     * Ajax remove a package
     * @param $packageId int
     */
    public function removePackageAction($packageId)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        try {
            if (empty($_POST['status']) && $_POST['name'] && $_POST['type']) {
                $this->packageManager->removePackageFromDisk($_POST['name'], $_POST['type']);
            } else {
                $this->packageManager->removePackage($packageId);
            }

            $this->cache->delete(Route::getCacheActiveRoutes());

            $this->returnJSON([
                'success' => true,
                'message' => 'Package removed successfully'
            ]);
        } catch (Exception $e) {
            $this->returnJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ajax packages search
     */
    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $count = 0;
        $modules = [];
        foreach (array_diff(scandir(MODULES_PATH), ['..', '.']) as $dir) {
            if (is_dir(MODULES_PATH . "/" . $dir)) {
                $model = new Package();
                $model->setId("l$count");
                $model->setName($dir);
                $model->setType(PackageType::module);
                $modules[$dir] = $model->toArray();
                $count++;
            }
        }

        foreach (array_diff(scandir(WIDGETS_PATH), ['..', '.']) as $dir) {
            if (is_dir(WIDGETS_PATH . "/" . $dir)) {
                $model = new Package();
                $model->setId("l$count");
                $model->setName($dir);
                $model->setType(PackageType::widget);
                $modules[$dir] = $model->toArray();
                $count++;
            }
        }

        $model = Package::find()->toArray();

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
        $this->setTitle('Edit Package');
        $form = new AdminPackageEditForm();
        if (!$this->request->isPost()) {
            $model = Package::findFirstById($id);
            if (!$model) {
                $this->flash->error("Package was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $form->setEntity($model);
        }
        $this->view->setVar('form', $form);
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

        $form = new AdminPackageEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Package::findFirstById($id);
        } else {
            $model = new Package();
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

        if ($model->getType() == PackageType::module) {
            $this->cache->delete(Package::getCacheActiveModules());
        } else {
            $this->cache->delete(Package::getCacheActiveWidgets());
        }

        $this->flash->success("Package was updated successfully");

        $this->cache->delete(Route::getCacheActiveRoutes());

        $this->response->redirect('admin/core/package-manager/index')->send();
        return;
    }
}