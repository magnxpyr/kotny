<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\View\Engine;

use Engine\Assets\Manager;

/**
 * Class Volt
 * @package Engine\Mvc\View\Engine
 */
class Php extends \Phalcon\Mvc\View\Engine\Php
{
    public function addViewJs($view)
    {
        $file = $this->getDI()->getView()->getBasePath(). $this->getDI()->getView()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file);
    }

    public function addViewCss($view)
    {
        $file = $this->getDI()->getView()->getBasePath(). $this->getDI()->getView()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addJs($file);
    }
}