<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

/**
 * Class View
 * @package Engine\Mvc
 */
class View extends \Phalcon\Mvc\View
{
    const SITE = 'site',
        WIDGET = 'widget';

    const DEFAULT_THEME = 'default',
        DEFAULT_BACKEND_THEME = 'adminlte',
        DEFAULT_LAYOUT = 'default',
        DEFAULT_WIDGET_LAYOUT = 'widget';

    private $theme;

    /**
     * Get current selected theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set current theme
     *
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Get current picked view
     *
     * @return array
     */
    public function getPickView()
    {
        return $this->_pickView;
    }

    /**
     * @inheritdoc
     */
    public function render($controllerName, $actionName, $params = null)
    {
        if ($this->getVar('viewType') == self::SITE) {
            $isBackend = $this->getDI()->get('helper')->isBackend();

            if ($isBackend) {
                $this->setBackendDefaults($controllerName, $actionName);
            } else {
                $this->setDefaults($controllerName, $actionName);
            }
        }

        parent::render($controllerName, $actionName, $params);
    }

    private function setDefaults($controllerName, $actionName)
    {
        /** @var Config\ConfigSample $config */
        $config = $this->getDI()->getShared('config');
        $theme = $config->defaultTheme;

        if ($this->getLayoutsDir() !== null) {
            $this->setLayoutsDir(THEMES_PATH . self::DEFAULT_THEME . '/layouts/');
        }
        if ($this->getLayout() !== null) {
            if (!empty($this->getLayout())) {
                if ($this->viewExists(THEMES_PATH . $theme . '/layouts/' . $this->getLayout())) {
                    $this->setLayoutsDir(THEMES_PATH . $theme . '/layouts/');
                }
            } else {
                $this->setLayout($config->defaultLayout);
                if ($this->viewExists(THEMES_PATH . $theme . '/layouts/' . $this->getLayout())) {
                    $this->setLayoutsDir(THEMES_PATH . $theme . '/layouts/');
                }
            }
        }

        if ($this->getMainView() !== null) {
            if (!empty($this->getMainView())) {
                if ($this->viewExists(THEMES_PATH . $theme . '/index')) {
                    $this->setMainView(THEMES_PATH . $theme . '/index');
                }
            } else {
                $this->setMainView(THEMES_PATH . self::DEFAULT_THEME . '/index');
            }
        }

        $oViewName = $this->getPickView();

        if ($oViewName != null) {
            $viewName = $oViewName[0];
        } else {
            $viewName = $controllerName . '/' . $actionName;
        }

        $moduleName = $this->getDI()->get('router')->getModuleName();

        if ($this->viewExists(THEMES_PATH . "$theme/views/modules/$moduleName/$viewName")) {
            $this->setBasePath(null);
            $this->setViewsDir(THEMES_PATH . "$theme/views/modules/$moduleName/");
        }
    }

    private function setBackendDefaults($controllerName, $actionName)
    {
        /** @var Config\ConfigSample $config */
        $config = $this->getDI()->getShared('config');
        $theme = $config->defaultTheme;

        if ($this->getBasePath() !== null) {
            $this->setBasePath(MODULES_PATH);
        }
        if ($this->getPartialsDir() !== null) {
            $this->setPartialsDir(THEMES_PATH . self::DEFAULT_BACKEND_THEME . '/partials/');
        }
        if ($this->getLayoutsDir() !== null) {
            $this->setLayoutsDir(THEMES_PATH . self::DEFAULT_BACKEND_THEME  . '/layouts/');
        }
        if ($this->getLayout() !== null) {
            $this->setLayout(self::DEFAULT_LAYOUT);
        }
        if ($this->getMainView() !== null) {
            $this->setMainView(THEMES_PATH . self::DEFAULT_BACKEND_THEME . '/index');
        }

        $oViewName = $this->getPickView();

        if ($oViewName != null) {
            $viewName = $oViewName[0];
        } else {
            $viewName = $controllerName . '/' . $actionName;
        }

        $moduleName = $this->getDI()->get('router')->getModuleName();

        if ($this->viewExists(THEMES_PATH . "$theme/views/modules/$moduleName/$viewName")) {
            $this->setBasePath(null);
            $this->setViewsDir(THEMES_PATH . "$theme/views/modules/$moduleName/");
        }
    }

    /**
     * Check if a full path view file exists without specifying extension
     *
     * @param $path
     * @return bool
     */
    public function viewExists($path) {
        return (file_exists($path . '.phtml') || file_exists($path . '.volt'));
    }
}