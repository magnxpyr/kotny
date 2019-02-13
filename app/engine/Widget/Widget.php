<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;

use Engine\Di\Injectable;
use Engine\Mvc\View;
use Module\Core\Models\Package;
use Phalcon\Mvc\View\Exception;
use Phalcon\Text;

/**
 * Class Widget
 * @package Engine\Widget
 */
class Widget extends Injectable
{
    const CACHE_PREFIX = 'widget_',
        CACHE_LIFETIME = 300;

    /**
     * Get widget cache key prefix.
     *
     * @param $widgetName string
     * @return string
     */
    public function getCachePrefix($widgetName)
    {
        return Widget::CACHE_PREFIX . $widgetName .  '-';
    }

    /**
     * Create cache key
     *
     * @param $widget string|array
     * @param $params
     * @param $widgetName
     * @return string
     */
    public function createCacheKey($widget, $params, $widgetName)
    {
        return $this->getCachePrefix($widgetName) .
            md5(serialize($widget).serialize($params).$this->auth->getUserRoleId());
    }

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

        if (isset($options['view'])) {
            $viewName = $options['view'];
        }

        $layout = null;
        if (isset($options['layout'])) {
            $layout = $options['layout'];
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
//        if ($options !== null && isset($options['cache']) && !empty($options['cache'])) {
//            if (!isset($params['cacheKey'])) {
//                $options['cacheKey'] = $this->createCacheKey($widget, $params, $widgetName);
//            }
//            if ($controller->cache->exists($options['cacheKey'], $options['cache'])) {
//                return $controller->cache->get($options['cacheKey']);
//            }
//        }

        if ($params !== null) {
            $controller->setParams($params);
        }
        if ($options !== null && isset($options['renderView'])) {
            $controller->setRenderView($options['renderView']);
        }
        // initialize the controller with the necessary settings
//        $controller->initialize();

        $viewWidget = clone $this->di->get('viewWidget');
        $controller->viewWidget = $this->di->get('viewWidget');

        $isBackend = (substr($controllerName, 0, 5) === "Admin");
        if ($isBackend) {
            // get params before calling the controller to have the necessary data for admin form
            $this->getAdminParams($controller);
        }

        $controller->{"{$action}Action"}();
        $html = null;
        if ($controller->getRenderView()) {
            if ($isBackend) {
                $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/Views");
                $controller->viewWidget->pick($viewName);
                $controller->viewWidget->setRenderLevel(View::LEVEL_ACTION_VIEW);
            } else {
                $this->getWidgetDefaults($controller, $widgetName, $viewName, $layout);
                $controller->viewWidget->setVar('params', $params);
            }
            $html = $controller->viewWidget->getRender($controllerName, $action);
        }
        $this->di->set('viewWidget', $viewWidget);

//        if ($html != null && $options !== null && isset($options['cache']) && !empty($options['cache'])) {
//            $controller->cache->save($options['cacheKey'], $html, $options['cache']);
//        }
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

    private function getWidgetDefaults(&$controller, $widgetName, $viewName, $layout)
    {
        $pickView = $controller->viewWidget->getPickView();
        if ($pickView !== null) {
            $viewName = $pickView[0];
        }

        $controller->viewWidget->setViewsDir(APP_PATH . "widgets/$widgetName/Views");
        $controller->viewWidget->pick([$viewName]);

        $viewsDir = THEMES_PATH . $this->config->defaultTheme . '/views/widgets/' .
            Text::uncamelize($widgetName, '-') . '/';
        if ($controller->viewWidget->viewExists($viewsDir . $viewName)) {
            $controller->viewWidget->setViewsDir($viewsDir);
        }

        if ($controller->viewWidget->getLayoutsDir() !== null) {
            $controller->viewWidget->setLayoutsDir(THEMES_PATH . View::DEFAULT_THEME . '/layouts/');
        }

//        $layout = $controller->viewWidget->getLayout();

        if (!empty($layout)) {
            if ($controller->viewWidget->viewExists(THEMES_PATH . $this->config->defaultTheme . '/layouts/' . $layout)) {
                $controller->viewWidget->setLayoutsDir(THEMES_PATH . $this->config->defaultTheme . '/layouts/');
            }
        } else {
            $controller->viewWidget->setLayout(View::DEFAULT_WIDGET_LAYOUT);
            if ($controller->viewWidget->viewExists(THEMES_PATH . $this->config->defaultTheme . '/layouts/' . View::DEFAULT_WIDGET_LAYOUT)) {
                $controller->viewWidget->setLayoutsDir(THEMES_PATH . $this->config->defaultTheme . '/layouts/');
            }
        }

    }

    private function getAdminParams(&$controller)
    {
        $dbParams = [];
        $params = $controller->getParams();
        if ($params['id']) {
            $model = \Module\Core\Models\Widget::findFirstById($params['id']);
            if ($model->getParams()) {
                $dbParams = (array) json_decode($model->getParams());
            }
        }

        $params = array_merge($dbParams, $dbParams);

        $controller->setParams($params);
        $controller->viewWidget->setVar('params', $params);
    }
}