<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;
use Phalcon\Mvc\View;

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
        if ($options !== null && $options['cache']) {
            if (!isset($params['cache_key'])) {
                $options['cache_key'] = $controller->createCacheKey($widget, $params);
            }
            if ($controller->cache->exists($options['cache_key'], 300)) {
                echo $controller->cache->get($options['cache_key']);;
                return;
            }
        }

        if ($params !== null) {
            $controller->setParams($params);
        }
        if ($options !== null && isset($options['renderView'])) {
            $controller->setRenderView($options['renderView']);
        }
        $controller->initialize();
        $controller->{"{$action}Action"}();
        if ($controller->getRenderView()) {
            $controller->viewWidget->start();
            $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/");
            $controller->viewWidget->pick($action);
            $controller->viewWidget->render('controller', $action);
            $controller->viewWidget->finish();
        }

        $html = $controller->viewWidget->getContent();
        if ($options !== null && $options['cache']) {
            $controller->cache->save($options['cache_key'], $html, 300);
        }
        echo $html;
        return;
    }
}