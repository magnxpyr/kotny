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

    const OUTPUT_VIEW_CSS = 'output-view-css',
        OUTPUT_VIEW_JS = 'output-view-js';

    public function __construct($options = null)
    {
        parent::__construct();
        $this->getDI();
    }

    public function includeInlineJs($file, $attributes)
    {
        $moduleName = Text::camelize($this->dispatcher->getModuleName());
        $controllerName = $this->di->getDispatcher()->getControllerName();
        $path = MODULES_PATH . "$moduleName/Views/$controllerName/$file";
        $text = file_get_contents($path);
        foreach ($attributes as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
        $this->addInlineJs($text);
    }

    public function outputViewJs()
    {
        $collection = $this->collection(self::OUTPUT_VIEW_JS);
        foreach ($collection as $resource) {
            echo $this->di->getViewSimple()->render($resource->getPath());
        }
    }

    public function outputViewCss()
    {
        $collection = $this->collection(self::OUTPUT_VIEW_CSS);
        foreach ($collection as $resource) {
            echo $this->di->getViewSimple()->render($resource->getPath());
        }
    }
}