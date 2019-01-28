<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace engine\Mvc\View\Engine;

use Engine\Assets\Manager;

/**
 * Trait EngineTrait
 * @package engine\Mvc\View\Engine
 */
trait ViewEngineTrait
{
    public function addViewJs($view, $params = null)
    {
        $file = $this->getDI()->getView()->getBasePath() . $this->getViewsDir($this->getDI()->getView()->getViewsDir()) . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file, true, null, $params);
    }

    public function addViewCss($view, $params = null)
    {
        $file = $this->getDI()->getView()->getBasePath() . $this->getViewsDir($this->getDI()->getView()->getViewsDir()) . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addCss($file, true, null, $params);
    }

    public function addViewWidgetJs($view, $params = null)
    {
        $file = $this->getViewsDir($this->getDI()->getViewWidget()->getViewsDir()) . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file, true, null, $params);
    }

    public function addViewWidgetCss($view, $params = null)
    {
        $file = $this->getViewsDir($this->getDI()->getViewWidget()->getViewsDir()) . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addCss($file, true, null, $params);
    }

    private function getViewsDir($views)
    {
        if (is_array($views)) {
            $views = count($views) > 0 ? $views[0] : '';
        }
        return $views;
    }
}