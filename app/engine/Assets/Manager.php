<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
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

    public function outputViewJs()
    {
        $collection = $this->collection(self::OUTPUT_VIEW_JS);
        foreach ($collection as $resource) {
            echo $this->di->getViewSimple()->render($resource->getPath(), $resource->getAttributes());
        }
    }

    public function outputViewCss()
    {
        $collection = $this->collection(self::OUTPUT_VIEW_CSS);
        foreach ($collection as $resource) {
            echo $this->di->getViewSimple()->render($resource->getPath(), $resource->getAttributes());
        }
    }
}