<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Tools\Builder;

use Module\Tools\Helpers\Tools;

/**
 * Class Module
 * @package Tools\Builder
 */
class Module extends Component
{
    /**
     * Controller constructor
     *
     * @param array $options
     * @throws \Exception
     */
    public function __construct($options)
    {
        if (empty($options['name'])) {
            throw new \Exception("Please specify the module name");
        }
        if (empty($options['directory'])) {
            $options['directory'] = Tools::getModulesPath() . $options['name'] . DIRECTORY_SEPARATOR;
        } else {
            $options['directory'] .= DIRECTORY_SEPARATOR;
        }
        if (empty($options['namespace']) || $options['namespace'] != 'None') {
            $options['namespace'] = Tools::getBaseNamespace() . "\\" . $options['name'];
        }
        if (empty($options['routes'])) {
            $options['routes'] = false;
        }
        if (empty($options['force'])) {
            $options['force'] = false;
        }
        $this->_options = $options;
    }

    /**
     * Build the controller
     *
     * @return string
     * @throws \Exception
     */
    public function build()
    {
        if (!is_dir($this->_options['directory']) || $this->_options['force'] == true) {
            if (!is_dir($this->_options['directory'])) {
                if(!@mkdir($this->_options['directory']))
                    throw new \Exception("Unable to create module directory!");
                @chmod($this->_options['directory'], 0777);
            }
        } else {
            throw new \Exception("Module directory already exists!");
        }

        if(!is_dir($this->_options['directory'] . Tools::getControllersDir())) {
            if (!@mkdir($this->_options['directory'] . Tools::getControllersDir()))
                throw new \Exception("Unable to create controller directory!");
            @chmod($this->_options['directory'] . Tools::getControllersDir(), 0777);
        }

        $controller = new Controller(array(
            'module' => $this->_options['name'],
            'name' => 'IndexController',
            'namespace' => null,
            'baseClass' => current(Tools::getBaseController()),
            'directory' => $this->_options['directory'] . DIRECTORY_SEPARATOR . Tools::getControllersDir(),
            'force' => $this->_options['force']
        ));
        $controller->build();

        if(!is_dir($this->_options['directory'] . Tools::getMigrationsDir())) {
            if (!@mkdir($this->_options['directory'] . Tools::getMigrationsDir()))
                throw new \Exception("Unable to create model directory!");
            @chmod($this->_options['directory'] . Tools::getMigrationsDir(), 0777);
        }

        if(!is_dir($this->_options['directory'] . Tools::getModelsDir())) {
            if (!@mkdir($this->_options['directory'] . Tools::getModelsDir()))
                throw new \Exception("Unable to create model directory!");
            @chmod($this->_options['directory'] . Tools::getModelsDir(), 0777);
        }

        if(!is_dir($this->_options['directory'] . Tools::getFormsDir())) {
            if (!@mkdir($this->_options['directory'] . Tools::getFormsDir()))
                throw new \Exception("Unable to create form directory!");
            @chmod($this->_options['directory'] . Tools::getFormsDir(), 0777);
        }

        if(!is_dir($this->_options['directory'] . Tools::getViewsDir())) {
            if (!@mkdir($this->_options['directory'] . Tools::getViewsDir()))
                throw new \Exception("Unable to create controller directory!");
            @chmod($this->_options['directory'] . Tools::getViewsDir(), 0777);
        }

        $view = new View([
            'name' => 'IndexController',
            'module' => $this->_options['name'],
            'force' => $this->_options['force']
        ]);
        $view->build();

        if($this->_options['routes']) {
            $this->_createRoute();
        }

        $this->_createModule();
        $this->createPackageJson();
    }

    /**
     * Generate route file
     * @throws \Exception
     */
    private function _createRoute()
    {
        $code = "<?php\n".Tools::getCopyright()."\n\nnamespace ".$this->_options['namespace'].';'.PHP_EOL.PHP_EOL;
        $baseRoute = Tools::getBaseRoute();
        if(!empty($baseRoute)) {
            $base = explode('\\', Tools::getBaseRoute());
            $baseClass = end($base);

            $useClass = 'use '.Tools::getBaseRoute().';'.PHP_EOL.PHP_EOL;
            $code .= $useClass;
        }

        $code .= "/**
 * Class Routes
 * @package " . $this->_options['namespace'] . "
 */
class Routes";
        if(!empty($baseRoute)) {
            $code .= " extends $baseClass";
        }
        $code .= "\n{\n\t/**
     * Add routes
     * @param \\Phalcon\\Mvc\\Router \$router
     */
    public function init(\$router)
    {
        \$router->add('/:module/:controller/:action/:params', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ));\n\t}\n}";
        $code = str_replace("\t", "    ", $code);

        $routePath = $this->_options['directory'] . DIRECTORY_SEPARATOR . 'Routes.php';
        if (!file_exists($routePath) || $this->_options['force'] == true) {
            if (!@file_put_contents($routePath, $code)) {
                throw new \Exception("Unable to write to '$routePath'");
            }
            @chmod($routePath, 0777);
        } else {
            throw new \Exception("Routes.php file already exists");
        }
    }

    /**
     * Generate module file
     * @throws \Exception
     */
    private function _createModule()
    {
        $code = "<?php\n".Tools::getCopyright().PHP_EOL.PHP_EOL;

        $code .= "return [\n\t'allow' => [\n\t\t'guest' => [\n\t\t\t'index' => ['index']\n\t\t]\n\t]\n];";

        $code = str_replace("\t", "    ", $code);

        $aclPath = $this->_options['directory'] . DIRECTORY_SEPARATOR . 'Acl.php';
        if (!file_exists($aclPath) || $this->_options['force'] == true) {
            if (!@file_put_contents($aclPath, $code)) {
                throw new \Exception("Unable to write to '$aclPath'");
            }
            @chmod($aclPath, 0777);
        } else {
            throw new \Exception("Module.php file already exists");
        }
    }

    /**
     * Create package.json file
     * @throws \Exception
     */
    private function createPackageJson()
    {
        $code = "{
    \"package\": \"" . $this->_options['name'] . "\",
    \"type\": \"module\",
    \"version\": \"1.0.0\",
    \"author\": \"\",
    \"website\": \"\",
    \"description\": \"\",
    \"licence\": \"\"
}";

        $code = str_replace("\t", "    ", $code);

        $path = $this->_options['directory'] . DIRECTORY_SEPARATOR . 'package.json';
        if (!file_exists($path) || $this->_options['force'] == true) {
            if (!@file_put_contents($path, $code)) {
                throw new \Exception("Unable to write to '$path'");
            }
            @chmod($path, 0777);
        } else {
            throw new \Exception("package.json file already exists");
        }
    }
}