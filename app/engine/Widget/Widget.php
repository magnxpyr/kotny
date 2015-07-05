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
class Widget extends Injectable
{
    public function render($widget, $params)
    {
        $view = $this->di->get('viewWidget');
        $view->setViewsDir(APP_PATH . 'modules/Core/Views/');
        $elements = explode('/', $widget);
        $action = isset($elements[1]) ? $elements[1] : 'index';
        $view->getRender($elements[0], $action, $params);
    }
}