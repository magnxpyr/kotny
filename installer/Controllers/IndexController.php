<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Installer\Controllers;

use ConfigDefault;
use Engine\Mvc\Auth;
use Engine\Mvc\Config\ConfigSample;
use Installer\Forms\ConfigurationForm;
use Installer\Migrations\MigrationMigration;
use Installer\Migrations\PackageMigration;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Manager;

/**
 * Class IndexController
 * @package Installer\Controllers
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        if (file_exists(CONFIG_PATH . 'config-default.php')) {
            $this->view->setVar('success', true);
            return;
        }

        $this->createDirectories();

        $form = new ConfigurationForm();

        $dbArray = [
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'pdo_sqlite' => extension_loaded('pdo_sqlite'),
            'mysqli' => extension_loaded('mysqli'),
            'pdo' => extension_loaded('pdo')
        ];

        $db = [];
        foreach ($dbArray as $k => $v) {
            if ($v) {
                $db[] = $k;
            }
        }

        $recommended = [
            'zlib' => extension_loaded('zlib'),
            'magicQuotes' => (bool) ini_get('magic_quotes_runtime'),
            'fileUploads' => (bool) ini_get('file_uploads'),
            'displayErrors' => (bool) ini_get('display_errors'),
            'safeMode' => (bool) ini_get('safe_mode')
        ];

        $required = [
            'php' => phpversion(), //version_compare(phpversion(), "5.6", '>='),
            'phalcon' => phpversion('phalcon'),
            'zip' => function_exists('zip_open') && function_exists('zip_read'),
            'db' => implode(', ', $db),
            'json' => function_exists('json_encode') && function_exists('json_decode'),
            'mcrypt' => is_callable('mcrypt_encrypt'),

            'writableRoot' => is_writable(ROOT_PATH),
            'writableModules' => is_writable(MODULES_PATH),
            'writableWidgets' => is_writable(WIDGETS_PATH),
            'writableMedia' => is_writable(MEDIA_PATH),
            'writableConfig' => is_writable(CONFIG_PATH),
            'writableAssets' => is_writable(PUBLIC_PATH . 'assets/'),
            'writableLogs' => is_writable(LOGS_PATH),
            'writableCache' => is_writable(CACHE_PATH),
        ];

        $isValid = true;
        foreach ($required as $k => $v) {
            if (!$v) {
                $isValid = false;
            }
        }

        $this->view->setVars([
            'form' => $form,
            'recommended' => $recommended,
            'required' => $required,
            'isValid' => $isValid,
            'success' => false
        ]);
    }

    public function checkConfigurationAction()
    {
        $form = new ConfigurationForm();
        $response = ['success' => true];

        if (!$form->isValid($_POST)) {
            $response['success'] = false;
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $response['errors'][] = $message->getMessage();
            }
        }

        $checkDB = $this->checkDB();
        if ($checkDB != null) {
            $response['errors'][] = $checkDB->getMessage();
            $response['success'] = false;
        }

        $this->view->disable();
        $this->response->setJsonContent($response);
        $this->response->send();
    }

    /**
     * create config-default.php and install
     */
    public function installAction()
    {
        $this->view->disable();

        $response = ['success' => true];

        $checkDB = $this->checkDB();
        if ($checkDB != null) {
            $response['errors'][] = $checkDB->getMessage();
            $response['success'] = false;

            $this->response->setJsonContent($response);
            $this->response->send();
        } else {
            $this->getDI()->setShared("modelsManager", function () {
                $modelsManager = new Manager();
                $modelsManager->setModelPrefix($_POST['db-prefix']);
                return $modelsManager;
            });
        }

        try {
            $config = new ConfigSample();
            $config->dbAdaptor = $_POST['db-adapter'];
            $config->dbName = $_POST['db-name'];
            $config->dbHost = $_POST['db-hostname'];
            $config->dbPort = $_POST['db-port'];
            $config->dbUser = $_POST['db-username'];
            $config->dbPass = $_POST['db-password'];
            $config->dbPrefix = $_POST['db-prefix'];
            $config->baseUri = str_replace('/install/', '', $this->url->get('/'));
            $config->timezone = date_default_timezone_get();
            $config->cryptKey = $this->security->getRandom()->hex(Auth::SELECTOR_BYTES);

            $this->registryConfig->writeConfig($config);

            $this->di->setShared('config', function () use ($config) {
                require_once CONFIG_PATH . 'config-default.php';
                return new ConfigDefault();
            });

            $migration = new MigrationMigration();
            $migration->up();

            $package = new PackageMigration();
            $package->up();

            $this->packageManager->installModule('Core');
            $this->db->insert($config->dbPrefix . 'user',
                [$_POST['su-username'], $_POST['su-email'], $this->security->hash($_POST['su-password']), 3, 1, time()],
                ['username', 'email', 'password', 'role_id', 'status', 'created_at']);

            $widgets = ['Carousel','Content', 'GridView', 'Menu', 'SortableView', 'TopContent'];
            foreach ($widgets as $widget) {
                $this->packageManager->installWidget($widget);
            }
        } catch (\Exception $e) {
            $response['errors'][] = $e->getMessage();
            $response['success'] = false;
        }

        $this->response->setJsonContent($response);
        $this->response->send();
    }

    public function removeInstallerAction()
    {
        $response = ['success' => true];

        set_error_handler(function($errno, $errstr, $errfile, $errline){
            if($errno === E_WARNING){
                // make it more serious than a warning so it can be caught
                trigger_error($errstr, E_USER_ERROR);
                return true;
            } else {
                // fallback to default php error handler
                return false;
            }
        });

        try {
            $this->deleteDir(ROOT_PATH . 'installer');
        } catch (\Exception $e) {
            $response['success'] = false;
        } finally {
            restore_error_handler();
        }

        $this->view->disable();
        $this->response->setJsonContent($response);
        $this->response->send();
    }

    /**
     * Check db config
     *
     * @return \Exception|null
     */
    private function checkDB()
    {
        $dbConfig = [
            'host' => $_POST['db-hostname'],
            'username' => $_POST['db-username'],
            'password' => $_POST['db-password'],
            'port' => $_POST['db-port'],
            'dbname' => $_POST['db-name']
        ];

        $db = null;

        try {
            // Connect to db
            switch ($_POST['db-adapter']) {
                case 'oracle':
                    $db = new \Phalcon\Db\Adapter\Pdo\Oracle($dbConfig);
                    break;
                case 'postgresql':
                    $db = new \Phalcon\Db\Adapter\Pdo\Postgresql($dbConfig);
                    break;
                default:
                    $db = new \Phalcon\Db\Adapter\Pdo\Mysql($dbConfig);
                    break;
            }
        } catch (\Exception $e) {
            return $e;
        }

        if ($db != null) {
            $this->getDI()->setShared('db', $db);
        }

        return null;
    }

    /**
     * Deletes a directory recursively
     *
     * @param $dir
     * @return bool
     */
    public function deleteDir($dir)
    {
        $files = array_diff(scandir($dir), ['.','..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : @unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function createDirectories()
    {
        if (!is_dir(CACHE_PATH)) {
            @mkdir(CACHE_PATH);
        }

        if (!is_dir(CACHE_PATH . 'backend')) {
            @mkdir(CACHE_PATH . 'backend');
        }

        if (!is_dir(CACHE_PATH . 'tmp')) {
            @mkdir(CACHE_PATH . 'tmp');
        }

        if (!is_dir(CACHE_PATH . 'volt')) {
            @mkdir(CACHE_PATH . 'volt');
        }

        if (!is_dir(LOGS_PATH)) {
            @mkdir(LOGS_PATH);
        }
    }
}

