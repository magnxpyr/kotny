<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

namespace Engine\Assets;

use Engine\Behavior\DiBehavior;
use Phalcon\Text;

/**
 * Class Manager
 * @package Engine\Assets
 */
class Manager extends \Phalcon\Assets\Manager
{
    use DiBehavior;
    
    public function includeInlineJs($file, $attributes)
    {
        $moduleName = Text::camelize($this->getDI()->get('dispatcher')->getModuleName());
        $controllerName = $this->getDI()->get('dispatcher')->getControllerName();
        $path = MODULES_PATH . "$moduleName/Views/$controllerName/$file";
        $text = file_get_contents($path);
        foreach ($attributes as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
        $this->addInlineJs($text);
    }
}