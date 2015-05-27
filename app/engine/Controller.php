<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

/**
 * Base Controller
 * @package   Engine
 */
abstract class Controller extends \Phalcon\Mvc\Controller {

    /**
     * Initializes the controller
     * @return void
     */
    protected function initialize() {
        if(!$this->request->isAjax()) {
            $this->_setupAssets();
        }
    }

    /**
     * Setup assets
     * @return void
     */
    protected function _setupAssets() {
        $this->assets
            ->collection('header-js-min')
            ->setTargetPath(PUBLIC_PATH . 'assets/default/js/header.min.js')
            ->setTargetUri('assets/default/js/header.min.js')
            ->addJs('vendor/jquery/jquery-1.11.3.min.js')
            ->addJs('vendor/jquery/jquery-ui.min.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
            ->addJs('assets/default/js/mg.js')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
        $this->assets
            ->collection('header-js');
        $this->assets
            ->collection('header-css-min')
            ->setTargetPath(PUBLIC_PATH . 'assets/default/css/header.min.css')
            ->setTargetUri('assets/default/css/header.min.css')
            ->addCss('vendor/jquery/jquery-ui.min.css')
            ->addCss('assets/default/css/style.css')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->assets
            ->collection('header-css')
            ->addCss('vendor/bootstrap/css/bootstrap.min.css');
    }
}