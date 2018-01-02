<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

use Engine\TokenManager;
use Phalcon\Mvc\Model\Manager;

/**
 * Class Bootstrap
 * @var \Engine\Mvc\Helper $helper
 */
class Bootstrap
{
    private $di;
    private $modules;

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

        // Getting a request instance
        $request = new Phalcon\Http\Request();
        $this->di->setShared('request', $request);

        // Register helper
        $this->di->setShared('helper', function () {
            return new \Engine\Mvc\Helper();
        });

        $this->di->setShared('logger', function () {
            return new \Phalcon\Logger\Adapter\File(ROOT_PATH . 'logs/' . date('Y-m-d') . '.log');
        });

        // Generate urls
        $url = new Phalcon\Mvc\Url();
        $url->setBaseUri($this->getConfig()->baseUri);
        $url->setBasePath(ROOT_PATH);
        $this->di->setShared('url', $url);

        $this->initView();
        $this->initView(true);
        $this->initSimpleView();

        $this->di->setShared('cookies', function () {
            $cookies = new \Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(true);
            return $cookies;
        });

        // Start the session from file
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $this->di->setShared('session', $session);

        $response = new \Phalcon\Http\Response();
        $this->di->setShared('response', $response);

        $this->initSecurity();
        $this->initEventManager();

        // Register assets that will be loaded in every page
        $this->di->setShared('assets', function () {
            return new \Engine\Assets\Manager();
        });

        $this->di->setShared('packageManager', function () {
            return new \Engine\Package\Manager();
        });

        $escaper = new \Phalcon\Escaper();
        $this->di->setShared('escaper', $escaper);

        $this->di->setShared('filter', function () use ($escaper) {
            $filter = new \Phalcon\Filter();
            $filter->add('escapeHtml', function ($value) use ($escaper) {
                return $escaper->escapeHtml($value);
            });

            return $filter;
        });

        $this->di->setShared('section', function () {
            return new \Engine\Mvc\View\Section();
        });

        $this->initMail();
        $this->initFlash();
        $this->dispatch();
    }

    private function initConfig()
    {
        // Define internal variables
        define('DEFAULT_THEME', 'default');

        // Load config file
        require_once APP_PATH . 'config/config-default.php';
        $config = new ConfigDefault();
        if ($config->environment != 'none') {
            $configFile = APP_PATH . 'config/config-' . $config->environment . ".php";
            if (is_file($configFile)) {
                require_once $configFile;
                $class = "Config". \Phalcon\Text::camelize($config->environment);
                $config = new $class();
            }
        }

        define('DEV', $config->dev);

        date_default_timezone_set($config->timezone);

        // set cookies time
        $config->cookieExpire = time() + $config->cookieExpire;

        $dbConfig = [
            'host' => $config->dbHost,
            'username' => $config->dbUser,
            'password' => $config->dbPass,
            'port' => $config->dbPort,
            'dbname' => $config->dbName,
            'prefix' => $config->dbPrefix
        ];

        // Connect to db
        switch ($config->dbAdaptor) {
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

        $this->di->setShared("modelsManager", function () use ($config) {
            $modelsManager = new Manager();
            $modelsManager->setModelPrefix($config->dbPrefix);
            return $modelsManager;
        });

        $this->di->setShared('db', $db);
        $this->di->setShared('config', $config);
        $this->initCache();

        // Load loader
        require_once APP_PATH . 'engine/Loader.php';
        $loader = new \Engine\Loader();
        $loader->init();

        // Load modules
        $modulesList = \Module\Core\Models\Package::getActiveModules();

        // Registering the registry
        $registry = new \Phalcon\Registry();
        $registry->modules = $modulesList;
        $this->di->setShared('registry', $registry);

        $modulesConfig = $loader->modulesConfig($modulesList);
        $this->modules = $modulesConfig['modules'];

        // Register routers with default behavior
        // Set 'false' to disable default behavior. After that define all routes or you get 404
        $router = new Phalcon\Mvc\Router(false);
        $router->removeExtraSlashes(true);
        $router->setDefaults([
            'module' => 'core',
            'controller' => 'index',
            'action' => 'index'
        ]);

        foreach ($modulesConfig['routes'] as $routeClass) {
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
        $eventsManager->attach('dispatch:beforeDispatchLoop', function (\Phalcon\Events\Event $event, \Phalcon\Dispatcher $dispatcher) {
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

        if (!$widget) {
            $view->setBasePath(MODULES_PATH);
            $view->setPartialsDir(THEMES_PATH . DEFAULT_THEME . '/partials/');
            $view->setMainView(THEMES_PATH . DEFAULT_THEME . '/index');
            $view->setLayout(DEFAULT_THEME);
        }

        $view->registerEngines([
            '.phtml' => function($view, $di) {
                return new \Engine\Mvc\View\Engine\Php($view, $di);
            },
            '.volt' => function($view, $di) use ($widget) {
                $volt = new \Engine\Mvc\View\Engine\Volt($view, $di);
                if (DEV) {
                    // Prevent caching annoyances
                    $voltOptions['compileAlways'] = true;
                }
                $voltOptions['compiledPath'] = CACHE_PATH . 'volt/';
                $voltOptions['compiledSeparator'] = $widget ? 'widget_' : '_';
                $volt->setOptions($voltOptions);
                $volt->initCompiler();

                return $volt;
            }
        ]);

        if ($widget) {
            $this->di->setShared('viewWidget', $view);

            $this->di->setShared('widget', function () {
                return new Engine\Widget\Widget();
            });
        } else {
            $this->di->setShared('view', $view);
        }
    }

    private function initSimpleView()
    {
        $view = new \Phalcon\Mvc\View\Simple();
        $view->registerEngines($this->di->get('view')->getRegisteredEngines());
        $this->di->setShared('viewSimple', $view);
    }

    private function initSecurity()
    {
        $config = $this->getConfig();

        $this->di->setShared('auth', function () {
            return new Engine\Mvc\Auth();
        });

        $tokenManager = new TokenManager();
        $this->di->setShared('tokenManager', $tokenManager);

        // Set up crypt service
        $this->di->setShared('crypt', function () use ($config) {
            $crypt = new \Phalcon\Crypt();
            $crypt->setKey($config->cryptKey);
            return $crypt;
        });

        //  Set security options
        $this->di->setShared('security', function () {
            $security = new \Phalcon\Security();
            $security->setRandomBytes(\Engine\Mvc\Auth::TOKEN_BYTES);
            $security->setWorkFactor(\Engine\Mvc\Auth::WORK_FACTOR);
            $security->setDefaultHash(Phalcon\Security::CRYPT_DEFAULT);
            return $security;
        });

        $acl = null;
        switch ($config->aclAdapter) {
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
        $this->di->set('mail', function () use ($config) {
            $settings = [
                'from' => [
                    'name' => $config->fromMame,
                    'email' => $config->mailFrom,
                ],
                'driver' => $config->mailer,
                'viewsDir' => THEMES_PATH . DEFAULT_THEME . '/emails/'
            ];
            switch ($config->mailer) {
                case 'sendmail':
                    $settings['sendmail'] = $config->sendmail;
                    break;
                case 'smtp':
                    $smtp = [
                        'host' => $config->smtpHost,
                        'port' => $config->smtpPort,
                        'encryption' => $config->smtpSecure ? 'ssl' : '',
                        'username' => $config->smtpUser,
                        'password' => $config->smtpUser,
                    ];
                    $settings = array_merge($settings, $smtp);
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
            'notice' => 'alert alert-info alert-dismissible',
            'warning' => 'alert alert-warning alert-dismissible',
            'error' => 'alert alert-danger alert-dismissible'
        ];

        $this->di->setShared('flash', function () use ($flash) {
            return new \Phalcon\Flash\Direct($flash);
        });

        $this->di->setShared('flashSession', function () use ($flash) {
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
        switch ($config->cacheAdapter) {
            case 'file':
                $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, [
                    "cacheDir" => CACHE_PATH . 'backend/'
                ]);
                break;
            case 'memcache':
                $cache = new \Phalcon\Cache\Backend\Memcache(
                    $cacheFrontend, [
                    "host" => $config->cacheHost,
                    "port" => $config->cachePort,
                ]);
                break;
            case 'memcached':
                $cache = new \Phalcon\Cache\Backend\Libmemcached(
                    $cacheFrontend, [
                    "host" => $config->cacheHost,
                    "port" => $config->cachePort,
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
        $request = $this->di->getShared('request');

        if (DEV) {
            // Load development options
            new \Engine\Development($this->di);
        }

        // Handle the request
        $application = new \Phalcon\Mvc\Application($this->di);
        $application->registerModules($this->modules);


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
        try {
            echo $application->handle()->getContent();
        } catch (\Exception $e) {
            if (DEV) {
                throw $e;
            } else {
                $this->di->getShared('logger')->error('Page error: ' . $e->getMessage());
                $application->response->redirect([
                    'for' => 'core-default',
                    'module' => 'core',
                    'controller' => 'error',
                    'action' => 'show404'
                ]);
                $application->response->send();
            }
        }
    }

    /**
     * @return Config
     */
    private function getConfig()
    {
        return $this->di->getShared('config');
    }
}