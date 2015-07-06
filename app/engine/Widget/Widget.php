<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;
use Phalcon\Di\Injectable;
use Widgets\Menu\Controller;

/**
 * Class Widget
 * @package Engine\Widget
 */
class Widget extends Injectable
{
    /**
     * Render widget
     * $widget = widgetName
     * $widget = [widgetName, action]
     *
     * @param string|array $widget
     * @param null|array $params
     */
    public function render($widget, $params = null)
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
        $view = $controller->di->get('viewWidget');
        $view->setViewsDir(APP_PATH . "widgets/$widgetName/");
        $view->pick($action);
        $view->render('controller', $action, $params);
        print_r($view); die;
        $controller->{"{$action}Action"}();
    }
}