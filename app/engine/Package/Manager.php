<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Package;

use Engine\Behavior\DiBehavior;
use Engine\Mvc\Exception;
use Module\Core\Models\Package;
use Module\Core\Models\Migration as MigrationModel;
use Module\Core\Models\User;
use Phalcon\Config\Adapter\Json;
use Phalcon\Db\Column;

/**
 * Class Manager
 * @package Engine\Package
 */
class Manager
{
    use DiBehavior;

    const MIGRATION_FILE_NAME_PATTERN = '/^\d+_([\w_]+).php$/i';

    public function __construct()
    {
        $this->getDI();
    }

    /**
     * Unzip file, detect package type and install
     * @param $fileName
     * @throws Exception
     */
    public function installPackage($fileName)
    {
        $zip = new \ZipArchive();
        $tmpDir = TEMP_PATH . md5($fileName) . '/';
        if ($zip->open(TEMP_PATH . $fileName) === true) {
            $zip->extractTo($tmpDir);
            $zip->close();
            unlink(TEMP_PATH . $fileName);
        } else {
            throw new Exception("Failed to open archive $fileName");
        }

        $package = null;
        $dirs = scandir($tmpDir);
        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            if (file_exists("$tmpDir$dir/package.json")) {
                $package = $tmpDir . $dir;
                break;
            }
        }

        if ($package == null) {
            throw new Exception("Error while installing package $fileName: package.json not found");
        }

        $config = new Json("$package/package.json");
        switch ($config->type) {
            case PackageType::MODULE:
                if ($this->moveDir($tmpDir, MODULES_PATH, $config->package)) {
                    $this->installModule($config->package);
                }
                break;
            case PackageType::WIDGET:
                if ($this->moveDir($tmpDir, WIDGETS_PATH, $config->package)) {
                    $this->installWidget($config->package);
                }
                break;
        }
    }

    /**
     * Remove package based on type and package id
     * @param $packageType PackageType|int
     * @param $packageId int
     */
    public function removePackage($packageType, $packageId)
    {
        switch ($packageType) {
            case PackageType::MODULE:
                $this->removeModule($packageId);
                break;
            case PackageType::WIDGET:
                $this->removeWidget($packageId);
                break;
        }
    }

    /**
     * @param $module
     * @return null|Json
     */
    private function getModuleConfig($module)
    {
        $package = MODULES_PATH . "$module/package.json";
        if (file_exists($package)) {
            return new Json($package);
        }
        return null;
    }

    /**
     * Install Module
     * @param $moduleName
     * @throws Exception
     */
    public function installModule($moduleName)
    {
        $this->logger->debug("Installing module: $moduleName");
        $controllersPath = MODULES_PATH . "$moduleName/Controllers";

        $roles = $this->acl->getRoles();
        // remove admin from roles since already has access on everything
        if (($key = array_search('admin', $roles)) !== false) {
            unset($roles[$key]);
        }

        if (is_dir($controllersPath)) {
            $files = scandir($controllersPath);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $className = str_replace('.php', '', $file);

                $resourceName = str_replace('Controller', '', $className);
                $resourceName = 'module:core/' . $this->helper->uncamelize($resourceName);

                $controllerClass = "Module\\$moduleName\\Controllers\\$className";
                $controller = new $controllerClass();

                $actions = $this->getResourceAccess($controller);
                $this->acl->adapter->addResource($resourceName, $actions);
            }
        }

        $aclPath = MODULES_PATH . "$moduleName/Acl.php";
        if (file_exists($aclPath)) {
            $resources = require_once $aclPath;

            if (isset($resources['allow'])) {
                $this->buildAcl($resources['allow'], $moduleName, true);
            }
            if (isset($resources['deny'])) {
                $this->buildAcl($resources['deny'], $moduleName, false);
            }
        }

        $config = $this->getModuleConfig($moduleName);

        $model = new Package();
        $model->setName($moduleName);
        $model->setType(PackageType::MODULE);
        $model->setVersion($config->version);
        $model->setAuthor($config->author);
        $model->setWebsite($config->website);
        $model->setStatus(User::STATUS_ACTIVE);
        $model->setDescription($config->description);

        if (!$model->save()) {
            throw new Exception("Unable to save module to database");
        }

        try {
            $this->migrate(Migration::UP, PackageType::MODULE, $moduleName, $model->getId());
        } catch (Exception $exception) {
            $migration = MigrationModel::findFirst([
                'conditions' => 'package_type = ?1 and package_id = ?1',
                'bind' => [1 => PackageType::MODULE, 2 => $model->getId()],
                'bindTypes' => [Column::BIND_PARAM_STR, Column::BIND_PARAM_INT],
            ]);
            if (!$migration) {
                $model->delete();
                // clean acl db
            }
            throw new Exception("Module migration failed");
        }

        $this->cache->delete($this->acl->getCacheKey());
        $this->logger->debug("Module $moduleName installed successfully");
    }

    /**
     * Uninstall a module
     * @param int $moduleId
     */
    public function removeModule($moduleId)
    {
        $this->logger->debug("Removing module with id: $moduleId");
        /**
         * @var $model Module
         */
        $model = Package::findFirstById($moduleId);
        if (!$model) {
            $this->logger->debug("Module with id: $moduleId not found");
            return;
        }

        $controllersPath = MODULES_PATH . $model->getName() . "/Controllers";

        $files = scandir($controllersPath);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $className = str_replace('.php', '', $file);

            $resourceName = str_replace('Controller', '', $className);
            $resourceName = 'module:core/' . $this->helper->uncamelize($resourceName);

            $this->acl->adapter->dropResourceAccess($resourceName);
        }

        $this->migrate(Migration::DOWN, PackageType::MODULE, $model->getName(), $model->getId());
        $model->delete();
        $this->cache->delete(Package::getCacheActiveModules());
        $this->cache->delete($this->acl->getCacheKey());
        $this->helper->removeDir(MODULES_PATH . $model->getName());
        $this->logger->debug("Module " . $model->getName() . " with id $moduleId removed successfully");
    }

    /**
     * @param $widget
     * @return null|Json
     */
    private function getWidgetConfig($widget)
    {
        $package = WIDGETS_PATH . "$widget/package.json";
        if (file_exists($package)) {
            return new Json($package);
        }
        return null;
    }

    /**
     * Install widget
     * @param $widgetName
     * @throws Exception
     */
    public function installWidget($widgetName)
    {
        $this->logger->debug("Installing widget $widgetName");
        $config = $this->getWidgetConfig($widgetName);

        $model = new Package();
        $model->setName($widgetName);
        $model->setType(PackageType::WIDGET);
        $model->setVersion($config->version);
        $model->setAuthor($config->author);
        $model->setWebsite($config->website);
        $model->setStatus(User::STATUS_ACTIVE);
        $model->setDescription($config->description);

        if (!$model->save()) {
            throw new Exception("Unable to save widget to database");
        }

        try {
            $this->migrate(Migration::UP, PackageType::WIDGET, $widgetName, $model->getId());
        } catch (Exception $exception) {
            $migration = MigrationModel::findFirst([
                'conditions' => 'package_type = ?1 and package_id = ?1',
                'bind' => [1 => PackageType::WIDGET, 2 => $model->getId()],
                'bindTypes' => [Column::BIND_PARAM_STR, Column::BIND_PARAM_INT],
            ]);
            if (!$migration) {
                $model->delete();
            }
            throw new Exception("Widget migration failed");
        }

        $this->cache->delete($this->acl->getCacheKey());
        $this->logger->debug("Widget $widgetName installed successfully");
    }

    /**
     * @param $widgetId
     * @throws Exception
     */
    public function removeWidget($widgetId)
    {
        $this->logger->debug("Removing widget with id: $widgetId");
        /**
         * @var $model Package
         */
        $model = Package::findFirstById($widgetId);
        if (!$model) {
            throw new Exception("Widget with id: $widgetId not found");
        }

        $this->migrate(Migration::DOWN, PackageType::WIDGET, $model->getName(), $model->getId());

        $model->delete();
        $this->cache->delete(Package::getCacheActiveWidgets());
        $this->cache->delete($this->acl->getCacheKey());
        $this->helper->removeDir(WIDGETS_PATH . $model->getName());
        $this->logger->debug("Widget " . $model->getName() . " with id $widgetId removed successfully");
    }

    /**
     * @param $from
     * @param $to
     * @param $packageName
     * @param bool|true $overwrite
     * @return bool
     * @throws Exception
     */
    private function moveDir($from, $to, $packageName, $overwrite = true) {
        if (is_dir($to . $packageName)) {
            if ($overwrite) {
                $this->helper->removeDir($to . $packageName);
            } else {
                throw new Exception("Package already exist");
            }
        }
        if (!rename($from . $packageName, $to . $packageName)) {
            $this->helper->removeDir($from);
            throw new Exception("Failed installing package $packageName: package can't be moved to the respective path");
        }
        rmdir($from);
        return true;
    }

    /**
     * Get actions from a controller
     * @param string|\StdClass $class
     * @return array
     */
    private function getResourceAccess($class)
    {
        $functions = get_class_methods($class);
        $actions = ['*'];
        foreach ($functions as $function) {
            if (strpos($function, 'Action')) {
                $actions[] = str_replace('Action', '', $function);
            }
        }

        return $actions;
    }

    /**
     * Build Acl Object
     * @param $resources
     * @param $module
     * @param $allow
     */
    private function buildAcl($resources, $module, $allow)
    {
        $module = strtolower($module);
        foreach ($resources as $role => $resource) {
            foreach ($resource as $controller => $actions) {
                $this->acl->adapter->addResource("module:$module/$controller", $actions);
                foreach ($actions as $action) {
                    if ($allow) {
                        $this->acl->adapter->allow($role, "module:$module/$controller", $action);
                    } else {
                        $this->acl->adapter->deny($role, "module:$module/$controller", $action);
                    }
                }
            }
        }
    }

    /**
     * @param Migration|string $direction
     * @param PackageType|string $packageType
     * @param $packageName
     * @param $packageId
     * @param string|null $version
     * @throws Exception
     */
    public function migrate($direction, $packageType, $packageName, $packageId, $version = null)
    {
        $migrations = $this->getMigrations($packageType, $packageName);
        $versions = $this->getVersionLog($packageId, $packageType);

        if (empty($versions) && empty($migrations)) {
            return;
        }
        if (null === $version) {
            $version = max(array_merge($versions, array_keys($migrations)));
            $version = (int)$version;
        } else {
            if (0 != $version && !isset($migrations[$version])) {
                throw new Exception("$version is not a valid version");
            }
        }
        krsort($migrations);
        if ($direction === Migration::DOWN) {
            foreach ($migrations as $key => $migration) {
                $key = (int) $key;
                if ($key < $version) {
                    break;
                }
                if (in_array($key, $versions)) {
                    $this->executeMigration($migration, Migration::DOWN, $key, $packageType, $packageId);
                }
            }
        } else {
            foreach ($migrations as $key => $migration) {
                $key = (int) $key;
                if ($key > $version) {
                    break;
                }
                if (!in_array($key, $versions)) {
                    $this->executeMigration($migration, Migration::UP, $key, $packageType, $packageId);
                }
            }
        }
    }

    /**
     * Executes the specified Migrations on this environment
     * @param Migration $migration
     * @param Migration|string $direction
     * @param $version
     * @param $packageType
     * @param $packageId
     */
    public function executeMigration(Migration $migration, $direction = Migration::UP, $version, $packageType, $packageId)
    {
        $startTime = time();
        // begin the transaction
        $this->db->begin();

        // Run the Migrations
        if (method_exists($migration, $direction)) {
            $migration->{$direction}();
        }
        // commit the transaction
        if ($this->db->commit()) {
            // Record it in the database
            $this->migrated($migration, $direction, $version, $packageType, $packageId, $startTime);
        } else {
            $this->db->rollback();
        }
    }

    /**
     * Gets an array of the database migrations, indexed by Migrations name (aka version) and sorted in ascending order
     * @param $packageType
     * @param $packageName
     * @return array
     * @throws Exception
     */
    public function getMigrations($packageType, $packageName)
    {
        $phpFiles = $this->getMigrationFiles($packageType, $packageName);
        // filter the files to only get the ones that match our naming scheme
        $fileNames = [];
        $versions = [];
        foreach ($phpFiles as $filePath) {
            $version = $this->getVersionFromFileName(basename($filePath));
            if (isset($versions[$version])) {
                throw new Exception("Duplicate Migrations - '$filePath' has the same version as '" . $version . "'");
            }
            // convert the filename to a class name
            $class = $this->mapFileNameToClassName(basename($filePath));
            if (isset($fileNames[$class])) {
                throw new Exception("Migration '" . basename($filePath) . "' has the same name as '" . $fileNames[$class] . "'");
            }
            $fileNames[$class] = basename($filePath);
            // load the Migrations file
            /** @noinspection PhpIncludeInspection */
            require_once $filePath;
            if (!class_exists($class)) {
                throw new Exception("Could not find class '$class' in file '$filePath'");
            }
            // instantiate it
            $migration = new $class();
            if (!($migration instanceof Migration)) {
                throw new Exception("The class '$class' in file '$filePath' must extend \\Engine\\Package\\Migration");
            }
            $versions[(int)$version] = $migration;
        }
        ksort($versions);

        return $versions;
    }

    /**
     * Returns a list of Migrations files found in the provided Migrations paths.
     *
     * @param $packageType
     * @param $packageName
     * @return array
     */
    private function getMigrationFiles($packageType, $packageName)
    {
        $path = null;
        switch ($packageType) {
            case PackageType::WIDGET:
                $path = WIDGETS_PATH . $packageName;
                break;
            case PackageType::MODULE:
                $path = MODULES_PATH . $packageName;
        }

        if ($path == null) {
            return [];
        }

        $files = glob($path . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '*.php');

        return $files;
    }

    /**
     * Get the version from the beginning of a file name.
     *
     * @param string $fileName File Name
     * @return string
     */
    private function getVersionFromFileName($fileName)
    {
        $matches = [];
        preg_match('/^[0-9]+/', basename($fileName), $matches);
        return $matches[0];
    }

    /**
     * Turn file names like '12345678901234_create_user_table.php' into class
     * names like 'CreateUserTable'.
     *
     * @param string $fileName File Name
     * @return string
     */
    private function mapFileNameToClassName($fileName)
    {
        $matches = array();
        if (preg_match(static::MIGRATION_FILE_NAME_PATTERN, $fileName, $matches)) {
            $fileName = $matches[1];
        }
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $fileName)));
    }

    public function getVersionLog($packageId, $packageType)
    {
        $rows = MigrationModel::find([
            'conditions' => 'package_type = ?1 and package_id = ?2',
            'bind' => [1 => $packageType, 2 => (int)$packageId],
            'bindTypes' => [Column::BIND_PARAM_STR, Column::BIND_PARAM_INT],
            'order' => 'version ASC'
        ])->toArray();

        $result = [];
        foreach($rows as $version) {
            /** @var $version MigrationModel */
            $result[$version['version']] = $version['version'];
        }
        return $result;
    }

    public function getCurrentVersion($packageId, $packageType, $versions = null)
    {
        if ($versions == null) {
            $versions = $this->getVersionLog($packageId, $packageType);
        }
        $version = 0;
        if (!empty($versions)) {
            $version = end($versions);
        }
        return $version;
    }

    /**
     * @param Migration $migration
     * @param Migration|string $direction
     * @param string $version
     * @param PackageType $packageType
     * @param integer $packageId
     * @param integer $startTime
     * @return $this
     */
    public function migrated(Migration $migration, $direction, $version, $packageType, $packageId, $startTime)
    {
        if ($direction == Migration::UP) {
            // up
            $model = new MigrationModel();
            $model->setName(get_class($migration));
            $model->setVersion($version);
            $model->setPackageId((int)$packageId);
            $model->setPackageType($packageType);
            $model->setStartTime($startTime);
            $model->setEndTime(time());
            $model->save();
        } else {
            // down
            MigrationModel::findFirst([
                'conditions' => 'version = ?1 and package_type = ?2 and package_id = ?3',
                'bind' => [1 => $version, 2 => $packageType, 3 => (int)$packageId],
                'bindTypes' => [Column::BIND_PARAM_STR, Column::BIND_PARAM_STR, Column::BIND_PARAM_INT],
                'order' => 'version ASC'
            ])->delete();
        }
        return $this;
    }
}