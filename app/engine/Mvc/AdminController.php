<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

/**
 * Base Admin Controller
 * @package   Engine
 */
abstract class AdminController extends Controller
{
    /**
     * Initializes the controller
     * @return void
     */
    protected function initialize()
    {
        $this->view->setVar('title', '');
        if ($this->request->isAjax()) {
            return;
        }
        $this->view->setMainView(THEMES_PATH . DEFAULT_THEME . '/admin');
        $this->view->setLayout('admin');
        $this->setupAssets();
        $this->view->setVar('navigation', $this->setupNavigation());
        $this->view->setVar('sidebar_collapse', isset($_COOKIE['mg-sdbrClp']) && $_COOKIE['mg-sdbrClp'] == 1 ? 'sidebar-collapse' : '');
    }

    /**
     * Setup assets
     * @return void
     */
    protected function setupAssets()
    {
        $this->assets->collection('header-css-min')
            ->setTargetPath('assets/mg_admin/css/header.min.css')
            ->setTargetUri('assets/mg_admin/css/header.min.css')
            ->addCss('vendor/jquery-ui/jquery-ui.min.css')
            ->addCss('assets/mg_admin/css/AdminLTE.min.css')
            ->addCss('assets/mg_admin/css/skins/skin-purple.min.css')
            ->addCss('assets/default/css/pdw.css')
            ->addCss('assets/mg_admin/css/style.css')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->assets->collection('header-css')
            ->addCss('vendor/bootstrap/css/bootstrap.min.css')
            ->addCss('vendor/font-awesome/css/font-awesome.min.css');

        $this->assets->collection('footer-js-min')
            ->setTargetPath(PUBLIC_PATH . 'assets/mg_admin/js/header.min.js')
            ->setTargetUri('assets/mg_admin/js/header.min.js')
            ->addJs('vendor/jquery/jquery-1.11.3.min.js')
            ->addJs('vendor/jquery-ui/jquery-ui.min.js')
            ->addJs('vendor/js/js.cookie.js')
            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
            ->addJs('vendor/jquery/extra/jquery.slimscroll.min.js')
            ->addJs('assets/mg_admin/js/app.js')
            ->addJs('assets/default/js/pdw.js')
            ->addJs('assets/mg_admin/js/mg.js')
            ->join(true)
            ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
        $this->assets->collection('footer-js');
    }

    /**
     * Build Navigation Menu
     * @return array
     */
    protected function setupNavigation()
    {
        $navigation = [
            'admin' => [
                'href' => 'admin',
                'title' => 'Dashboard',
                'prepend' => '<i class="fa fa-dashboard"></i>'
            ],
            'tools' => [
                'title' => 'Web Tools',
                'prepend' => '<i class="fa fa-list-alt"></i>',
                'items' => [ // type - dropdown
                    'modules' => [
                        'title' => 'Modules',
                        'href' => 'admin/tools/modules/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'controllers' => [
                        'title' => 'Controllers',
                        'href' => 'admin/tools/controllers/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'models' => [
                        'title' => 'Models',
                        'href' => 'admin/tools/models/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'migrations' => [
                        'title' => 'Migrations',
                        'href' => 'admin/tools/migrations/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'scaffold' => [
                        'title' => 'Scaffold',
                        'href' => 'admin/tools/scaffold/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ]
                ]
            ],
            'user_roles' => [
                'title' => 'Users & Roles',
                'prepend' => '<i class="fa fa-group"></i>',
                'items' => [
                    'user' => [
                        'title' => 'Users',
                        'href' => 'admin/core/user/index',
                        'prepend' => '<i class="fa fa-user"></i>'
                    ]
                ]
            ],
            'menus' => [
                'title' => 'Menu Types',
                'prepend' => '<i class="fa fa-th-list"></i>',
                'items' => [
                    'menu-type' => [
                        'title' => 'Menus',
                        'href' => 'admin/core/menu-type/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ],
                    'menu-items' => [
                        'title' => 'Menu Items',
                        'href' => 'admin/core/menu/index',
                        'prepend' => '<i class="fa fa-circle-o"></i>'
                    ]
                ]
            ]
        ];

        $html = $this->renderItems($navigation);
        return $html;
    }

    /**
     * Render Navigation Items
     * @param array $items
     * @param int $isActive
     * @return array
     */
    protected function renderItems($items, $isActive = 0)
    {
        $content = ['html' => '', 'breadcrumb' => '', 'active' => $isActive];
        if ($this->router->getMatchedRoute()->getName() == 'admin-tools') {
            $controller = $this->router->getControllerName();
        } else {
            $controller = str_replace('admin-', '', $this->router->getControllerName());
        }
        $route = 'admin/'.$this->router->getModuleName().'/'.$controller;
        foreach ($items as $item) {
            if (!empty($item['items'])) {
                $result = $this->renderItems($item['items'], $content['active']);
                if($result['active'] == 2)  {
                    $content['breadcrumb'] .= "<li>".$item['prepend'].' '.$item['title']."</li>".$result['breadcrumb'];
                }
                $active = $result['active'] == 2 ? 'active' : '';
                $content['html'] .= "<li class=\"treeview $active\">";
                $content['html'] .= sprintf("<a href=\"#\">%s</i><span>%s</span><i class=\"fa fa-angle-left pull-right\"></i></a>", $item['prepend'], $item['title']);
                $content['html'] .= "<ul class=\"treeview-menu\">";
                $content['active'] = $result['active'];
                $content['html'] .= $result['html'];
                $content['html'] .= "</ul></li>";
            } else {
                if($content['active'] < 2 && isset($item['href'])) {
                    $content['active'] = strpos($item['href'], $route) !== false ? 1 : 0;
                }
                if($content['active'] == 1) {
                    $content['html'] .= '<li class="active">';
                    $content['breadcrumb'] .= '<li class="active">'.$item['title'].'</li>';
                    $content['active'] = 2;
                } else {
                    $content['html'] .= '<li>';
                }
                $content['html'] .= sprintf("<a href=\"%s\">%s<span>%s</span></a></li>", $this->url->get($item['href']), $item['prepend'], $item['title']);
            }
        }
        return $content;
    }
}