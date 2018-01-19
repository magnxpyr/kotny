<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace engine\Mvc\Config;

use Engine\Mvc\Exception;
use Phalcon\Text;

/**
 * Class Registry
 * @package Engine\Mvc\Config
 */
class ConfigRegistry
{
    /**
     * Converts an object into a php class string.
     * Only one depth level is supported.
     *
     * @param $object ConfigSample
     * @param array $params
     * @return string
     */
    public function objectToString($object, $params = [])
    {
        // A class must be provided
        $class = !empty($params['class']) ? $params['class'] : 'Config' . Text::camelize($object->environment);

        // Build the object variables string
        $vars = '';

        foreach (get_object_vars($object) as $k => $v)
        {
            if (is_scalar($v))
            {
                $vars .= "\tpublic $" . $k . " = '" . addcslashes($v, '\\\'') . "';\n";
            }
            elseif (is_array($v) || is_object($v))
            {
                $vars .= "\tpublic $" . $k . " = " . $this->getArrayString((array) $v) . ";\n";
            }
        }

        $str = "<?php\n";

        // If supplied, add a namespace to the class object
        if (isset($params['namespace']) && $params['namespace'] != '')
        {
            $str .= "namespace " . $params['namespace'] . ";\n\n";
        }

        $str .= "class " . $class . " {\n";
        $str .= $vars;
        $str .= "}";

        // Use the closing tag if it not set to false in parameters.
        if (!isset($params['closingtag']) || $params['closingtag'] !== false)
        {
            $str .= "\n?>";
        }

        return $str;
    }

    public function writeConfig($object, $params = [])
    {
        $file = APP_PATH . "config/config-$object->environment.php";
        if (!file_put_contents($file, $this->objectToString($object, $params))) {
            throw new Exception("Unable to write config file");
        }
    }

    protected function getArrayString($a)
    {
        $s = '[';
        $i = 0;

        foreach ($a as $k => $v) {
            $s .= ($i) ? ', ' : '';
            $s .= '"' . $k . '" => ';

            if (is_array($v) || is_object($v)) {
                $s .= $this->getArrayString((array) $v);
            } else {
                $s .= '"' . addslashes($v) . '"';
            }

            $i++;
        }

        $s .= ']';

        return $s;
    }

}