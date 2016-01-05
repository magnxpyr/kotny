<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
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
     * Cache prefix.
     */
    public $cache_prefix = 'widget_';

    /**
     * @var \Phalcon\Mvc\View() $viewWidget
     */
    public $viewWidget;

    /**
     * Widget Name
     *
     * @var string
     */
    public $widgetName;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var bool
     */
    private $renderView = true;

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
     * Get widget option.
     *
     * @param string $key     Option name.
     * @param null   $default Option default value.
     * @return null
     */
    public function getOption($key, $default = null)
    {
        if (!isset($this->options[$key])) {
            return $default;
        }
        return $this->options[$key];
    }

    /**
     * Get all widget options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set all widget options
     * @param $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Set a widget option
     *
     * @param string|int $key
     * @param string|int|array $value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * @return bool
     */
    public function getRenderView()
    {
        return $this->renderView;
    }

    /**
     * @param bool $value
     */
    public function setRenderView($value)
    {
        $this->renderView = $value;
    }

    /**
     * Get widget cache key.
     *
     * @return string|null
     */
    public function getCacheKey()
    {
        if (isset($this->options['cache_key'])) {
            return $this->cache_prefix . $this->options['cache_key'];
        }
        if (isset($this->options['content_id'])) {
            return $this->cache_prefix . $this->widgetName . $this->options['content_id'];
        }
        return null;
    }
    /**
     * Get widget cache lifetime.
     *
     * @return int
     */
    public function getCacheLifeTime()
    {
        return 300;
    }
    /**
     * Clear widget cache.
     *
     * @return void
     */
    public function clearCache()
    {
        $key = $this->getCacheKey();
        if ($key) {
            $cache = $this->getDI()->get('cache');
            $cache->delete($key);
        }
    }

    /**
     * Create cache key
     *
     * @param $widget
     * @param $params
     * @return string
     */
    public function createCacheKey($widget, $params)
    {
        return md5(serialize($widget).serialize($params).$this->auth->getUserRole());
    }

    /**
     * Initialize the widget
     */
    public function initialize()
    {
        if ($this->getRenderView()) {
            $this->viewWidget = $this->di->get('viewWidget');
        }
    }
}