<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/
namespace Module\Tools\Controllers;

use Engine\Package\Migration;
use Engine\Package\PackageType;
use Module\Core\Models\Package;
use Module\Tools\Builder\Migrations;
use Module\Tools\Helpers\Tools;

/**
 * Class MigrationsController
 * @package Tools\Controllers
 */
class AdminMigrationsController extends ControllerBase
{
    public function indexAction()
    {
        $this->setTitle("Generate Migration");

        $selectedModule = null;
        $params = $this->router->getParams();
        if(!empty($params))
            $selectedModule = $this->router->getParams()[0];
        $this->view->selectedModule = $selectedModule;

        $this->listTables(true);
    }

    /**
     * Generates migrations
     */
    public function generateAction()
    {
        if ($this->request->isPost()) {
            $exportData = '';
            $packageType = $this->request->getPost('packageType', 'string');
            $package = $this->request->getPost('package', 'string');
            $tableName = $this->request->getPost('table-name', 'string');

            $migrationsDir = $this->_getMigrationsDir();

            try {
                Migrations::generate(array(
                    'config' => Tools::getConfig(),
                    'tableName' => $tableName,
                    'exportData' => $exportData,
                    'migrationsDir' => Tools::getPackagePath($packageType) . "$package/$migrationsDir/",
                ));

                $this->flash->success("The Migrations was generated successfully");
            } catch (\Exception $e) {
             $this->flash->error($e->getMessage());
            }
        }

        $this->dispatcher->forward([
            'action' => 'index'
        ]);
        return;
    }

    /**
     * Run Migrations
     */
    public function runAction()
    {
        $this->setTitle("Run Migration");

        $selectedModule = null;
        $params = $this->router->getParams();
        if(!empty($params))
            $selectedModule = $this->router->getParams()[0];
        $this->view->selectedModule = $selectedModule;

        if ($this->request->isPost()) {
            $packageType = $this->request->getPost('packageType', 'string');
            $package = $this->request->getPost('package', 'string');

            try {
                /** @var Package $model */
                $model = Package::findFirstByName($package);
                if (!$model) {
                    $this->flash->error("Package does not exist or is not installed");
                    return;
                }

                $this->packageManager->migrate(Migration::UP, $packageType, $package, $model->getId());

                $this->flash->success("The Migrations was executed successfully");
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
    }

    /**
     * @param $packageType PackageType
     */
    public function changePackageAction($packageType)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $this->returnJSON(['packages' => Tools::listPackages($packageType), 'success' => true]);
        return;
    }

    /**
     * @throws \Exception
     * @return string
     */
    private function _getMigrationsDir()
    {
        $migrationsDir = Tools::getMigrationsDir();
        if (!file_exists($migrationsDir)) {
            if(!@mkdir($migrationsDir)) {
                throw new \Exception("Unable to create Migrations directory on ".Tools::getMigrationsDir());
            }
            @chmod($migrationsDir, 0777);
        }

        return $migrationsDir;
    }
}
