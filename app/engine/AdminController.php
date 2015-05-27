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
        if($this->request->isAjax()) {
            return;
        }
    //    parent::initialize();
        $this->view->setMainView(THEMES_PATH . 'admin');
        $this->view->setLayout('admin');
        $this->_setupAssets();
    }

    /**
     * Setup assets
     * @return void
     */
    private function _setupAssets() {
        $this->assets
            ->collection('header-js-min')
            ->setTargetPath(PUBLIC_PATH . 'assets/mg_admin/js/header.min.js')
            ->setTargetUri('assets/mg_admin/js/header.min.js')
            ->addJs('vendor/jquery/jquery-1.11.3.min.js')
            ->addJs('vendor/jquery/jquery-ui.min.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
            ->addJs('assets/default/js/mg.js')
            ->addJs('assets/mg_admin/js/app.min.js')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
        $this->assets
            ->collection('header-js');
        $this->assets
            ->collection('header-css-min')
            ->setTargetPath(PUBLIC_PATH . 'assets/mg_admin/css/header.min.css')
            ->setTargetUri('assets/mg_admin/css/header.min.css')
            ->addCss('vendor/jquery/jquery-ui.min.css')
            ->addCss('assets/mg_admin/css/AdminLTE.min.css')
            ->addCss('assets/mg_admin/css/skins/skin-blue.min.css')
            ->addCss('assets/mg_admin/css/style.css')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->assets
            ->collection('header-css')
            ->addCss('vendor/bootstrap/css/bootstrap.min.css')
            ->addCss('vendor/font-awesome/css/font-awesome.min.css');
    }



}