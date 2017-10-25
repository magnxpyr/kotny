<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 24.10.2017
 * Time: 22:11
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
        $file = $this->getDI()->getView()->getBasePath(). $this->getDI()->getView()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file, true, null, $params);
    }

    public function addViewCss($view, $params = null)
    {
        $file = $this->getDI()->getView()->getBasePath(). $this->getDI()->getView()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addCss($file, true, null, $params);
    }

    public function addViewWidgetJs($view, $params = null)
    {
        $file = $this->getDI()->getViewWidget()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file, true, null, $params);
    }

    public function addViewWidgetCss($view, $params = null)
    {
        $file = $this->getDI()->getViewWidget()->getViewsDir() . $view;
        $this->getDI()->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addCss($file, true, null, $params);
    }
}