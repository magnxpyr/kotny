<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Cache\Backend\Memory;

/**
 * Class Bootstrap
 */
class Bootstrap {
    private $di;

    public function __construct()
    {
        $this->di = new \Phalcon\DI\FactoryDefault();
    }

    public function run()
    {
        define('DEV', true);

        $this->initLoader();
        $this->initView();
        $this->initRegistry();
        $this->initCache();
        $this->initSecurity();
        $this->initUrl();
        $this->initConfigRegistry();
        $this->initPackageManager();
        $this->initLogger();
        $this->initHelper();
        $this->dispatch();
    }

    private function dispatch()
    {
        // Handle the request
        $application = new \Phalcon\Mvc\Application($this->di);
        echo $application->handle()->getContent();
    }


    private function initView()
    {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(ROOT_PATH . 'installer/Views/');
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $view->registerEngines([
            '.phtml' => \Phalcon\Mvc\View\Engine\Php::class
        ]);

        $this->di->setShared('view', $view);
    }

    private function initCache()
    {
        $this->di->setShared('cache', new Memory(new \Phalcon\Cache\Frontend\Data()));
    }

    private function initRegistry()
    {
        $registry = new \Phalcon\Registry();
        $registry->modules = [];
        $this->di->setShared('registry', $registry);
    }

    private function initUrl()
    {
        $url = new Phalcon\Mvc\Url();
        $url->setBaseUri($_SERVER['REQUEST_URI']);
        $url->setBasePath(ROOT_PATH);
        $this->di->setShared('url', $url);
    }

    private function initLoader()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces([
            'Installer' => ROOT_PATH . 'installer/',
            'Engine' => APP_PATH . 'engine/',
            'Module' => MODULES_PATH
        ]);
        $loader->register();

        $router = new Phalcon\Mvc\Router();
        $router->removeExtraSlashes(true);
        $router->setDefaults([
            'namespace' => 'Installer\Controllers'
        ]);
        $router->add('/:action', [
            'controller' => 'index',
            'action' => 1
        ]);

        require_once APP_PATH . 'vendor/autoload.php';

        $this->di->setShared('router', $router);
    }

    private function initSecurity()
    {
        $acl = new \Engine\Acl\Memory();
        $this->di->setShared('acl', $acl->getAcl());

        $this->di->setShared('security', function () {
            $security = new \Phalcon\Security();
            $security->setRandomBytes(\Engine\Mvc\Auth::TOKEN_BYTES);
            $security->setWorkFactor(\Engine\Mvc\Auth::WORK_FACTOR);
            $security->setDefaultHash(Phalcon\Security::CRYPT_DEFAULT);
            return $security;
        });
    }

    private function initLogger()
    {
        $this->di->setShared('logger', new \Phalcon\Logger\Adapter\Stream('php://stderr'));
    }

    private function initConfigRegistry()
    {
        $this->di->setShared('registryConfig', new \Engine\Mvc\Config\ConfigRegistry());
    }

    private function initPackageManager()
    {
        $this->di->setShared('packageManager', new \Engine\Package\Manager());
    }

    private function initHelper()
    {
        $this->di->setShared('helper', new \Engine\Mvc\Helper());
    }
}