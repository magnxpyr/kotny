<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Installer\Controllers;

use Engine\Mvc\Auth;
use Engine\Mvc\Config\ConfigSample;
use Installer\Forms\ConfigurationForm;
use Phalcon\Mvc\Controller;

/**
 * Class IndexController
 * @package Installer\Controllers
 */
class IndexController extends Controller
{
    public function indexAction()
    {
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
            'isValid' => $isValid
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

    // create config-default.php and install

    public function install()
    {
        $config = new ConfigSample();
        $config->dbAdaptor = $_POST['db-adapter'];
        $config->dbName = $_POST['db-name'];
        $config->dbHost = $_POST['db-hostname'];
        $config->dbPort = $_POST['db-port'];
        $config->dbUser = $_POST['db-username'];
        $config->dbPass = $_POST['db-password'];
        $config->timezone = timezone_location_get();
        $config->cryptKey = $this->security->getRandom()->hex(Auth::SELECTOR_BYTES);

        $this->registry->writeConfig($config);
    }

    private function checkDB()
    {
        $dbConfig = [
            'host' => $_POST['db-hostname'],
            'username' => $_POST['db-username'],
            'password' => $_POST['db-password'],
            'port' => $_POST['db-port'],
            'dbname' => $_POST['db-name']
        ];

        try {
            // Connect to db
            switch ($_POST['db-adapter']) {
                case 'oracle':
                    new \Phalcon\Db\Adapter\Pdo\Oracle($dbConfig);
                    break;
                case 'postgresql':
                    new \Phalcon\Db\Adapter\Pdo\Postgresql($dbConfig);
                    break;
                default:
                    new \Phalcon\Db\Adapter\Pdo\Mysql($dbConfig);
                    break;
            }
        } catch (\Exception $e) {
            return $e;
        }

        return null;
    }
}

