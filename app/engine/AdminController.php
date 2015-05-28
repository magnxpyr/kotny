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
        $this->view->setMainView(THEMES_PATH . 'admin');
        $this->view->setLayout('admin');
        $this->_setupAssets();
        $this->view->navigation = $this->_setupNavigation();
        $this->view->sidebar_collapse = isset($_COOKIE['sidebar-collapse']) && $_COOKIE['sidebar-collapse'] == 1 ? 'sidebar-collapse' : '';
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
            ->addJs('vendor/jquery/jquery.cookie.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
            ->addJs('assets/default/js/mg.js')
            ->addJs('assets/mg_admin/js/app.js')
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
        $navigation = [
            'admin' => [
                'href' => '/admin',
                'title' => 'Dashboard',
                'prepend' => '<i class="glyphicon glyphicon-home"></i>'
            ],
            'tools' => [
                'title' => 'Web Tools',
                'prepend' => '<i class="glyphicon glyphicon-user"></i>',
                'items' => [ // type - dropdown
                    'users' => [
                        'title' => 'Modules',
                        'href' => '/admin/tools/modules/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'pages' => [
                        'title' => 'Controllers',
                        'href' => '/admin/tools/controllers/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'menus' => [
                        'title' => 'Models',
                        'href' => '/admin/tools/models/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'languages' => [
                        'title' => 'Migrations',
                        'href' => '/admin/tools/migrations/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'files' => [
                        'title' => 'Scaffold',
                        'href' => '/admin/tools/scaffold/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ]
                ]
            ]
        ];

        $html = $this->_renderItems($navigation);
        return $html['html'];
    }

    protected function _renderItems($items, $isActive = 0) {
        $content = ['html' => '', 'active' => $isActive];
        $route = '/admin/'.$this->router->getModuleName().'/'.$this->router->getControllerName();
        foreach($items as $item) {
            if($content['active'] != 3 && isset($item['href'])) {
                $content['active'] = strpos($item['href'], $route) !== false ? 1 : 0;
            }
            if (!empty($item['items'])) {
                $content['html'] .= "<li class=\"treeview\">";
                $content['html'] .= sprintf("<a href=\"#\">%s</i><span>%s</span><i class=\"fa fa-angle-left pull-right\"></i></a>", $item['prepend'], $item['title']);
                $content['html'] .= "<ul class=\"treeview-menu\">";
                $result = $this->_renderItems($item['items'], $content['active']);
                $content['active'] = $result['active'];
                $content['html'] .= $result['html'];
                $content['html'] .= "</ul></li>";
            } else {
                if($content['active'] == 1) {
                    $content['html'] .= '<li class="active">';
                    $content['active'] = 3;
                } else {
                    $content['html'] .= '<li>';
                }
                $content['html'] .= sprintf("<a href=\"%s\">%s<span>%s</span></a></li>", $this->url->get($item['href']), $item['prepend'], $item['title']);
            }
        }
        return $content;
    }
}