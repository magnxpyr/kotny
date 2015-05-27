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
    protected function _setupAssets() {
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

    protected function _setupNavigation() {
        return [
            'admin' => [
                'href' => 'admin',
                'title' => 'Dashboard',
                'prepend' => '<i class="glyphicon glyphicon-home"></i>'
            ],
            'users' => [
                'title' => 'Manage',
                'items' => [ // type - dropdown
                    'admin/users' => [
                        'title' => 'Users and Roles',
                        'href' => 'admin/users',
                        'prepend' => '<i class="glyphicon glyphicon-user"></i>'
                    ],
                    'admin/pages' => [
                        'title' => 'Pages',
                        'href' => 'admin/pages',
                        'prepend' => '<i class="glyphicon glyphicon-list-alt"></i>'
                    ],
                    'admin/menus' => [
                        'title' => 'Menus',
                        'href' => 'admin/menus',
                        'prepend' => '<i class="glyphicon glyphicon-th-list"></i>'
                    ],
                    'admin/languages' => [
                        'title' => 'Languages',
                        'href' => 'admin/languages',
                        'prepend' => '<i class="glyphicon glyphicon-globe"></i>'
                    ],
                    'admin/files' => [
                        'title' => 'Files',
                        'href' => 'admin/files',
                        'prepend' => '<i class="glyphicon glyphicon-file"></i>'
                    ],
                    'admin/packages' => [
                        'title' => 'Packages',
                        'href' => 'admin/packages',
                        'prepend' => '<i class="glyphicon glyphicon-th"></i>'
                    ]
                ]
            ],
        ];
    }

    protected function _renderItems($items, $isActive = false) {
        $content = '';
        foreach($items as $item) {
            if (isset($item['items']) && !empty($item['items'])) {
                $content = $this->_renderChild($content, $item['title'], $item, $isActive);
            } else {
                $content = $this->_renderTopLevel($content, $item['title'], $item, $isActive);
            }
        }
        return $content;
    }

    protected function _renderTopLevel($content, $title, $items, $isActive = false) {
        foreach($items as $item) {

        }
        return $content;
    }

    protected function _renderChild($content, $title, $items, $isActive = false) {
        foreach($items as $item) {

        }
        return $content;
    }
}