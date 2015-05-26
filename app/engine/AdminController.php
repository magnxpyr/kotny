<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

/**
 * Base Admin Controller
 * @package   Engine
 */
abstract class AdminController extends Controller {

    /**
     * Initializes the controller
     * @return void
     */
    protected function initialize() {
        parent::initialize();
        if(!$this->request->isAjax()) {
            return;
        }
        $this->view->setMainView(THEMES_PATH . 'admin');
        $this->view->setLayout('admin');
        $this->_setupAssets();
    }

    /**
     * Setup assets
     * @return void
     */
    protected function _setupAssets() {
        $this->assets
            ->collection('header-js')
            //    ->setTargetPath(PUBLIC_PATH . 'assets/default/js/header.min.js')
            //    ->setTargetUri('assets/default/js/header.min.js')
            ->addJs('vendor/jquery/jquery-1.11.3.min.js')
            ->addJs('vendor/jquery/jquery-ui.min.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
            ->addJs('assets/default/js/mg.js');
        //    ->join(true)
        //    ->addFilter(new Phalcon\Assets\Filters\Jsmin());
        $this->assets
            ->collection('header-css')
            //    ->setTargetPath(PUBLIC_PATH . 'assets/default/css/header.min.css')
            //    ->setTargetUri('assets/default/css/header.min.css')
            ->addCss('vendor/jquery/jquery-ui.min.css')
            ->addCss('vendor/bootstrap/css/bootstrap.min.css')
            ->addCss('assets/default/css/style.css');
        //    ->join(true)
        //    ->addFilter(new Phalcon\Assets\Filters\Cssmin());
    }

}