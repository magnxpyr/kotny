<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;
use Phalcon\Di\Injectable;

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
        $controller = new $controllerClass();
        $controller->initialize();
        $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/");
        $controller->{"{$action}Action"}($params);
        $controller->viewWidget->render('controller', $action);

    }
}