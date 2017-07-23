<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Mvc\AdminController;
use Engine\Mvc\Exception;
use Engine\Package\PackageType;
use Module\Core\Forms\AdminPackageManagerForm;

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
        switch ($packageType) {
            case PackageType::MODULE:
                $this->packageManager->installModule($package);
                break;
            case PackageType::WIDGET:
                $this->packageManager->installWidget($package);
                break;
        }
    }

    /**
     * Ajax remove a package
     * @param $packageId int
     * @param $packageType PackageType
     */
    public function removePackageAction($packageType, $packageId)
    {
        try {
            $this->packageManager->removePackage($packageType, $packageId);
            $this->flashSession->success("Package removed successfully");
            $this->returnJSON(['success' => true]);
        } catch (Exception $e) {
            $this->flashSession->error($e->getMessage());
        }
    }
}