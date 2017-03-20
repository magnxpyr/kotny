<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

use Engine\TokenManager;
use Phalcon\Events\Event;
use Phalcon\Dispatcher;

/**
 * Class Bootstrap
 * @var \Engine\Mvc\Helper $helper
 */
class Bootstrap
{
    private $di;

    public function __construct()
    {
        // The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
        $this->di = new \Phalcon\DI\FactoryDefault();
    }

    /**
     * Initialize and run application
     */
    public function run()
    {
        $this->initConfig();

        $config = $this->getConfig();

        // Getting a request instance
        $request = new Phalcon\Http\Request();
        $this->di->setShared('request', $request);

        // Register helper
        $this->di->setShared('helper', function() {
            return new \Engine\Mvc\Helper();
        });

        // Generate urls
        $url = new Phalcon\Mvc\Url();
        $url->setBaseUri($this->getConfig()->app->baseUri);
        $url->setBasePath(ROOT_PATH);
        $this->di->setShared('url', $url);

        $this->initView();
        $this->initView(true);

        $this->di->setShared('cookies', function() {
            $cookies = new \Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(true);
            return $cookies;
        });

        // Start the session from file
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $this->di->setShared('session', $session);

        // Connect to db
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql([
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname
        ]);
        $this->di->setShared('db', $db);

        $response = new \Phalcon\Http\Response();
        $this->di->setShared('response', $response);

        $this->initCache();
        $this->initSecurity();
        $this->initEventManager();
        
        // Register assets that will be loaded in every page
        $this->di->setShared('assets', function() {
            return new \Engine\Assets\Manager();
        });

        $escaper = new \Phalcon\Escaper();
        $this->di->setShared('escaper', $escaper);

        $this->di->setShared('filter', function() use ($escaper) {
            $filter = new \Phalcon\Filter();
            $filter->add('escapeHtml', function ($value) use ($escaper) {
                return $escaper->escapeHtml($value);
            });

            return $filter;
        });


        $this->di->setShared('logger', function() {
            return new \Phalcon\Logger\Adapter\File(ROOT_PATH . 'logs/' . date('d-M-Y') . '.log');
        });

        $this->initMail();
        $this->initFlash();
        $this->dispatch();
    }

    private function initConfig()
    {
        // Define internal variables
        define('DEFAULT_THEME', 'default');
        define('THEMES_PATH', APP_PATH . 'themes/');
        define('MODULES_PATH', APP_PATH . 'modules/');

        // Load config file
        $config = require_once APP_PATH . 'config/config.php';
        if ($config['app']['development']) {
            if (is_file(APP_PATH . 'config/development/config.php')) {
                $config = require_once APP_PATH . 'config/development/config.php';
            }
        }

        date_default_timezone_set($config['app']['timezone']);

        define('CACHE_PATH', ROOT_PATH . 'cache/');
        define('DEV', $config['app']['development']);

        // set cookies time
        $config['app']['cookie']['expire'] = time() + $config['app']['cookie']['expire'];

        // Load modules
        $modulesList = require_once APP_PATH . 'config/modules.php';

        // Registering the registry
        $registry = new \Phalcon\Registry();
        $registry->modules = $modulesList;
        $this->di->setShared('registry', $registry);

        // Load loader
        require_once APP_PATH . 'engine/Loader.php';
        $loader = new \Engine\Loader();
        $modulesConfig = $loader->modulesConfig($modulesList);
        $modulesRoutes = $modulesConfig['routes'];
        unset($modulesConfig['routes']);
        $config = new \Phalcon\Config(array_merge_recursive($config, $modulesConfig));
        $loader->init($config->loader->namespaces);
        $this->di->setShared('config', $config);


        // Register routers with default behavior
        // Set 'false' to disable default behavior. After that define all routes or you get 404
        $router = new Phalcon\Mvc\Router(false);
        $router->removeExtraSlashes(true);
        $router->setDefaults([
            'module' => 'core',
            'controller' => 'index',
            'action' => 'index'
        ]);

        foreach($modulesRoutes as $routeClass) {
            $route = new $routeClass;
            $route->init($router);
        }
        $this->di->setShared('router', $router);
    }

    private function initEventManager()
    {
        // Obtain the standard eventsManager from the DI
        $eventsManager = new \Phalcon\Events\Manager();

        //Registering a dispatcher
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        // Attach the Security plugin
        $eventsManager->attach('dispatch', new \Engine\Plugins\AclHandler());
        $eventsManager->attach('dispatch', new \Engine\Plugins\ErrorHandler());
        $eventsManager->attach('dispatch', new \Engine\Plugins\Translation());
        $eventsManager->attach('dispatch:beforeDispatchLoop', function(\Phalcon\Events\Event $event, \Phalcon\Dispatcher $dispatcher) {
            $dispatcher->setActionName(lcfirst(\Phalcon\Text::camelize($dispatcher->getActionName())));
        });

        //Bind the EventsManager to the Dispatcher
        $dispatcher->setEventsManager($eventsManager);

        $this->di->setShared('dispatcher', $dispatcher);
    }

    /**
     * @param $widget bool
     */
    private function initView($widget = false)
    {
        $view = new \Phalcon\Mvc\View();
        $view->setLayoutsDir(THEMES_PATH . DEFAULT_THEME . '/layouts/');

        if ($widget) {
            $view->setLayout('widget');
        } else {
            $view->setPartialsDir(THEMES_PATH . DEFAULT_THEME . '/partials/');
            $view->setMainView(THEMES_PATH . DEFAULT_THEME . '/index');
            $view->setLayout(DEFAULT_THEME);
        }

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $this->di);
        if(DEV) {
            // Prevent caching annoyances
            $voltOptions['compileAlways'] = true;
        }
        $voltOptions['compiledPath'] = CACHE_PATH . 'volt/';
        $voltOptions['compiledSeparator'] = $widget ? 'widget_' : '_';
        $volt->setOptions($voltOptions);
        $compiler = $volt->getCompiler();
        // add a function
        $compiler->addFunction(
            'f',
            function ($resolvedArgs, $exprArgs) {
                return 'function($model){ return ' . trim($resolvedArgs,"'\"") .';}';
            }
        );
        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $this->di);

        $view->registerEngines([
            '.volt' => $volt,
            '.phtml' => $phtml
        ]);

        if ($widget) {
            $this->di->setShared('viewWidget', $view);

            $this->di->setShared('widget', function() {
                return new Engine\Widget\Widget();
            });
        } else {
            $this->di->setShared('view', $view);
        }
    }

    private function initSecurity()
    {
        $config = $this->getConfig();

        $this->di->setShared('auth', function() {
            return new Engine\Mvc\Auth();
        });

        $tokenManager = new TokenManager();
        $this->di->setShared('tokenManager', $tokenManager);

        // Set up crypt service
        $this->di->setShared('crypt', function() use ($config) {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->app->cryptKey);
            return $crypt;
        });

        //  Set security options
        $this->di->setShared('security', function() {
            $security = new \Phalcon\Security();
            $security->setRandomBytes(\Engine\Mvc\Auth::TOKEN_BYTES);
            $security->setWorkFactor(\Engine\Mvc\Auth::WORK_FACTOR);
            $security->setDefaultHash(Phalcon\Security::CRYPT_DEFAULT);
            return $security;
        });

        $acl = null;
        switch($config->app->aclAdapter) {
            case 'memory':
                $acl = new \Engine\Acl\Memory();
                break;
            case 'database':
                $acl = new \Engine\Acl\Database();
                break;
        }

        // Register ACL to DI
        $this->di->setShared('acl', $acl->getAcl());
    }
    
    private function initMail()
    {
        $config = $this->getConfig();
        
        // Register mail service
        $this->di->set('mail', function() use ($config) {
            $settings = [
                'from' => $config->mail->from->toArray(),
                'driver' => $config->mail->driver,
                'viewsDir' => APP_PATH . 'themes/' . DEFAULT_THEME . '/emails/'
            ];
            switch($config->mail->driver) {
                case 'sendmail':
                    $settings['sendmail'] = $config->mail->sendmail;
                    break;
                case 'smtp':
                    $settings = array_merge($settings, $config->mail->smtp->toArray());
                    break;
            }
            return new \Phalcon\Mailer\Manager($settings);
        });
    }

    private function initFlash()
    {
        // Register the flash service with custom CSS classes
        $flash = [
            'success' => 'alert alert-success alert-dismissible',
            'notice'  => 'alert alert-info alert-dismissible',
            'warning' => 'alert alert-warning alert-dismissible',
            'error'   => 'alert alert-danger alert-dismissible'
        ];

        $this->di->setShared('flash', function() use ($flash) {
            return new \Phalcon\Flash\Direct($flash);
        });

        $this->di->setShared('flashSession', function() use ($flash) {
            return new \Phalcon\Flash\Session($flash);
        });
    }

    private function initCache()
    {
        $config = $this->getConfig();
        
        // Set cache
        $cacheFrontend = new \Phalcon\Cache\Frontend\Data([
            "lifetime" => 172800,
            "prefix" => '_',
        ]);

        $cache = null;
        switch ($config->app->cache->adapter) {
            case 'file':
                $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, [
                    "cacheDir" => CACHE_PATH . 'backend/'
                ]);
                break;
            case 'memcache':
                $cache = new \Phalcon\Cache\Backend\Memcache(
                    $cacheFrontend, [
                    "host" => $config->app->cache->host,
                    "port" => $config->app->cache->port,
                ]);
                break;
            case 'memcached':
                $cache = new \Phalcon\Cache\Backend\Libmemcached(
                    $cacheFrontend, [
                    "host" => $config->app->cache->host,
                    "port" => $config->app->cache->port,
                ]);
                break;
        }

        $this->di->setShared('cache', $cache);
        $this->di->setShared('modelsCache', $cache);

        // If the configuration specify the use of metadata adapter use it or use memory otherwise
        $this->di->setShared('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\MetaData\Memory();
        });
    }

    private function dispatch()
    {
        $config = $this->getConfig();
        $request = $this->di->getShared('request');

        if (DEV) {
            // Load development options
            new \Engine\Development($this->di);
        }

        // Handle the request
        $application = new \Phalcon\Mvc\Application($this->di);
        $application->registerModules($config->modules->toArray());
        $application->setDI($this->di);

        if ($request->isAjax() && $request->getHeader("X-CSRF-Token") != $this->di->getShared('tokenManager')->getToken()) {
            $obj = new \stdClass();
            $obj->success = false;
            $obj->html = "Invalid CSRF Token";

            $application->response->setJsonContent($obj);
            $application->response->setStatusCode(503);
            $application->response->send();
            return false;
        }

        // Render
        echo $application->handle()->getContent();
    }

    private function getConfig()
    {
        return $this->di->getShared('config');
    }
}