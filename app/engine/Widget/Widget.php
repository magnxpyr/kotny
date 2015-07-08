<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;

/**
 * Class Widget
 * @package Engine\Widget
 */
class Widget
{
    /**
     * Render widget
     * $widget = widgetName
     * $widget = [widgetName, action]
     *
     * @param string|array $widget
     * @param null|array $params
     * @param null|array $options
     */
    public function render($widget, $params = null, $options = null)
    {
        if (is_array($widget)) {
            $widgetName = $widget[0];
            $action = $widget[1];
        } else {
            $widgetName = $widget;
            $action = 'index';
        }

        $controllerClass = "\\Widget\\$widgetName\\Controller";

        /**
         * @var \Engine\Widget\Controller $controller
         */
        $controller = new $controllerClass();
        if ($params !== null) {
            $controller->setParams($params);
        }
        $controller->initialize();
        $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/");
        $controller->{"{$action}Action"}();
        $controller->viewWidget->render('controller', $action);
    }
}