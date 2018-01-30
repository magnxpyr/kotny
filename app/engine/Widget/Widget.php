<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;

use Engine\Di\Injectable;
use Module\Core\Models\Package;
use Phalcon\Mvc\View\Exception;
use Phalcon\Text;

/**
 * Class Widget
 * @package Engine\Widget
 */
class Widget extends Injectable
{
    /**
     * Render widget
     * $widget = widgetName
     * $widget = [
     *  'widget' => 'menu',
     *  'controller' => 'controller',
     *  'action' => 'index'
     * ]
     * 
     * $options = [
     *  'cacheKey' => 'widget-key',
     *  'renderView' => 'index',
     *  'cache' => true
     * ]
     *
     * @param string|array $widget
     * @param null|array $params - Params required to render the widget
     * @param null|array $options - Extra widget control options
     * @return string
     */
    public function render($widget, $params = null, $options = null)
    {
        $this->logger->debug("Rendering widget: " . $this->helper->arrayToString($widget));

        $controllerName = 'controller';
        $action = 'index';

        if (is_array($widget)) {
            $widgetName = $widget['widget'];
            if (isset($widget['controller'])) {
                $controllerName = $widget['controller'];
            }
            if (isset($widget['action'])) {
                $action = $widget['action'];
            }
        } else {
            $widgetName = $widget;
        }

        $viewName = $action;
        if (strpos($controllerName, 'admin') === 0 && !isset($widget['action'])) {
            $viewName = "admin-$action";
        }

        // Render widget only if is active
        if (!Package::isActiveWidget($widgetName)) {
            $this->logger->debug("Widget is not active and won't be rendered; widget: $widgetName");
            return null;
        }

        $controllerName = Text::camelize($controllerName);

        $controllerClass = "\Widget\\$widgetName\Controllers\\$controllerName";
        if (!class_exists($controllerClass)) {
            $this->logger->debug("Widget class does not exist; widget: $widgetName, class: $controllerClass");
            return null;
        }

        /** @var \Engine\Widget\Controller $controller */
        $controller = new $controllerClass();
        if ($options !== null && $options['cache']) {
            if (!isset($params['cacheKey'])) {
                $options['cacheKey'] = $controller->createCacheKey($widget, $params);
            }
            if ($controller->cache->exists($options['cacheKey'], 300)) {
                return $controller->cache->get($options['cacheKey']);
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
        $html = null;
        if ($controller->getRenderView()) {
            $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/Views");
            $controller->viewWidget->pick([$viewName]);
            $controller->viewWidget->setMainView($viewName);
            $controller->viewWidget->setLayout('widget');
            $html = $controller->viewWidget->getRender($controllerName, $action);
        }

        if ($html != null && $options !== null && $options['cache']) {
            $controller->cache->save($options['cacheKey'], $html, 300);
        }
        return $html;
    }

    /**
     * Render a widget view using viewSimple
     *
     * $widget = [
     *  'widget' => 'carousel',
     *  'view' => 'admin-index-scripts'
     * ]
     *
     * @param $widget
     * @return string
     */
    public function renderSimple($widget)
    {
        try {
            return $this->viewSimple->render(WIDGETS_PATH . $widget['widget'] . "/Views/" . $widget['view']);
        } catch (Exception $e) {
            return null;
        }
    }
}