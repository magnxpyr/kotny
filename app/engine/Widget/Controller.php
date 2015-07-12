<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;

/**
 * Class Controller
 * @package Engine\Widget
 */
abstract class Controller extends \Phalcon\Mvc\Controller
{
    /**
     * @var \Phalcon\Mvc\View() $viewWidget
     */
    public $viewWidget;

    /**
     * @var array $params
     */
    private $params = [];

    /**
     * Get widget parameter.
     *
     * @param string $key     Param name.
     * @param null   $default Param default value.
     * @return null
     */
    public function getParam($key, $default = null)
    {
        if (!isset($this->params[$key])) {
            return $default;
        }
        return $this->params[$key];
    }

    /**
     * Get all widget parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set all widget parameters
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Set a widget parameter
     *
     * @param string|int $key
     * @param string|int|array $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Initialize the widget
     */
    public function initialize()
    {
        $this->viewWidget = $this->di->get('viewWidget');
    }
}