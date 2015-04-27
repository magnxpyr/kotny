<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Tools\Builder;

use Tools\Helpers\Tools;

class View extends Component {

    public function __constructor($options) {
        if (empty($options['name'])) {
            throw new \Exception("Please specify the controller name");
        }
        if (empty($options['force'])) {
            $options['force'] = false;
        }
        if (empty($options['directory'])) {
            $options['directory'] = Tools::getModulesDir() . $options['module'] . Tools::getControllersDir();
        }
    }

    /**
     * Generate view
     */
    public function build() {
        if ($this->_options['namespace'] != 'None') {
            $namespace = 'namespace '.$this->_options['namespace'].';'.PHP_EOL.PHP_EOL;
        } else {
            $namespace = '';
        }

        $className = Text::camelize(str_replace(' ','_',$this->_options['name']))."Controller";

        $controllerPath = $this->_options['directory'] . DIRECTORY_SEPARATOR . $className . ".php";

        $base = explode('\\', $this->_options['baseClass']);
        $baseClass = end($base);

        $useClass = 'use '.$this->_options['baseClass'].';'.PHP_EOL.PHP_EOL;

        $code = "<?php\n".Tools::getCopyright()."\n\n".$namespace.$useClass."class $className extends $baseClass {
            \n\tpublic function indexAction() {\n\n\t}\n}";
        $code = str_replace("\t", "    ", $code);

        if (!file_exists($controllerPath) || $this->_options['force'] == true) {
            if (!@file_put_contents($controllerPath, $code)) {
                throw new \Exception("Unable to write to '$controllerPath'");
            }
        } else {
            throw new \Exception("The Controller '$className' already exists");
        }

        return $className . 'Controller.php';
    }
}