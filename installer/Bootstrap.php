<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

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
        $this->initLoader();
        $this->initView();
        $this->initUrl();
        $this->initRegistry();
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
            'Engine' => APP_PATH . 'engine/'
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

        $this->di->setShared('router', $router);
    }

    private function initRegistry()
    {
        $this->di->setShared("registry", new \Engine\Mvc\Config\Registry());
    }
}