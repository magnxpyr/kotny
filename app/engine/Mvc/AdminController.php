<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Module\Core\Models\Menu;
use Phalcon\Mvc\Model\EagerLoading\Loader;

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
    public function initialize()
    {
        $this->setMetaDefaults();
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
        $this->assets->collection('header-css-min');
//            ->setTargetPath('assets/mg_admin/css/header.min.css')
//            ->setTargetUri('assets/mg_admin/css/header.min.css')
//            ->addCss('vendor/jquery-ui/jquery-ui.min.css')
//            ->addCss('vendor/jquery/extra/DataTables/datatables.min.css')
//            ->addCss('assets/mg_admin/css/AdminLTE.min.css')
//            ->addCss('assets/mg_admin/css/skins/skin-blue.min.css')
//            ->addCss('assets/default/css/pdw.css')
//            ->addCss('assets/mg_admin/css/style.css')
//            ->join(true)
//            ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->assets->collection('header-css');
//            ->addCss('vendor/bootstrap/css/bootstrap.min.css')
//            ->addCss('vendor/font-awesome/css/font-awesome.min.css');

        $this->assets->collection('footer-js-min');
//            ->setTargetPath(PUBLIC_PATH . 'assets/mg_admin/js/header.min.js')
//            ->setTargetUri('assets/mg_admin/js/header.min.js')
//            ->addJs('vendor/jquery/jquery-1.11.3.min.js')
//            ->addJs('assets/common/js/mg.js')
//            ->addJs('vendor/jquery-ui/jquery-ui.min.js')
//            ->addJs('vendor/js/js.cookie.js')
//            ->addJs('vendor/bootstrap/js/bootstrap.min.js')
//            ->addJs('vendor/jquery/extra/jquery.slimscroll.min.js')
//            ->addJs('vendor/js/moment.js')
//            ->addJs('vendor/jquery/extra/DataTables/datatables.min.js')
//            ->addJs('vendor/jquery/extra/DataTables/datetime.js')
//            ->addJs('assets/mg_admin/js/app.js')
//            ->addJs('assets/default/js/pdw.js')
//            ->addJs('assets/mg_admin/js/mg.js')
//            ->join(true)
//            ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
        $this->assets->collection('footer-js');
    }

    /**
     * Build Navigation Menu
     * @return array
     */
    protected function setupNavigation()
    {
        $isActive = false;
        $breadcrumb = [];
        $ids = ['currentId' => 0, 'previousId' => 0, 'parentId' => 0];
        $content = ['html' => '', 'breadcrumb' => ''];
        $count = 0;

        $uri = trim($this->router->getRewriteUri(), "/ ");

        $menuElements = Loader::fromResultset(Menu::find([
            'conditions' => 'menu_type_id = 0',
            'order' => 'lft'
        ]), 'viewLevel');
        $level = 1;

        foreach ($menuElements as $elements) {
            if (!$this->acl->checkViewLevel($elements->viewLevel->getRolesArray())) continue;

            $active = "";
            if (!$isActive) {
                $this->generateBreadcrumbs($elements, $breadcrumb, $ids, $count);
            }
            if ($elements->getPath() != "#" && substr($elements->getPath(), 0, 4) != "http") {
                $path = $this->url->get($elements->getPath());
                if (!$isActive && $uri == trim($elements->getPath(), "/ ")) {
                    $active = "active";
                    $isActive = true;
                }
            } else {
                $path = $elements->getPath();
            }

            if ($elements->getLevel() <= $level) {
                if ($elements->getLevel() < $level) {
                    $content['html'] .= "</li>\n";
                    for ($i = $level - $elements->getLevel(); $i; $i--) {
                        $content['html'] .= "</ul></li>";
                    }
                }
                $content['html'] .= "<li class=\"treeview $active\"><a href='$path'>";
                if (!empty($elements->getPrepend())) {
                    $content['html'] .= "<i class=\"$elements->prepend\"></i>";
                }
                $content['html'] .= "<span>$elements->title</span></a>";
            } elseif ($elements->level > $level) {
                $content['html'] .= "<ul class=\"treeview-menu $active\"><li class='$active'><a href=\"$path\">";
                if (!empty($elements->getPrepend())) {
                    $content['html'] .= "<i class=\"$elements->prepend\"></i>";
                }
                $content['html'] .= "<span>$elements->title</span></a>";
            }

            $level = $elements->level;
        }

        $cb = count($breadcrumb) - 1;
        foreach ($breadcrumb as $k => $b) {
            $content['breadcrumb'] .= "<li>";
            if ($cb != $k)
                $content['breadcrumb'] .= "<a href=\"" . $b["link"] . "\">";
            if ($k == 0)
                $content['breadcrumb'] .= "<i class=\"" . $b["prepend"] . "\">";
            $content['breadcrumb'] .= "</i> " . $b['title'] . "</a></li>";
        }

        return $content;
    }

    /**
     * @param $elements
     * @param $breadcrumb
     * @param $ids
     * @param $count
     */
    private function generateBreadcrumbs($elements, &$breadcrumb, &$ids, &$count)
    {
        if ($elements->level == 1) {
            $count = 0;
            $breadcrumb = [];
            $this->setBreadcrumb($breadcrumb, $count, $elements);
            $ids = ['currentId' => $elements->getId(), 'previousId' => $elements->getParentId(), 'parentId' => $elements->getParentId()];
        } else {
            if ($ids['currentId'] == $elements->getParentId() || $ids['previousId'] == $elements->getParentId()) {
                if ($ids['parentId'] != $elements->getParentId())
                    $count++;
                $this->setBreadcrumb($breadcrumb, $count, $elements);
                $ids = ['currentId' => $elements->getId(), 'previousId' => $ids['currentId'], 'parentId' => $elements->getParentId()];
            } else {
                $found = false;
                foreach ($breadcrumb as $k => $v) {
                    if ($v['id'] == $elements->getParentId()) {
                        $found = true;
                    } elseif ($found) {
                        unset($breadcrumb[$k]);
                    }
                }
                if ($found) {
                    $count = count($breadcrumb);
                    $this->setBreadcrumb($breadcrumb, $count, $elements);
                    $ids = ['currentId' => $elements->getId(), 'previousId' => $ids['currentId'], 'parentId' => $elements->getParentId()];
                }
            }
        }
    }

    /**
     * @param $breadcrumb
     * @param $count
     * @param $elements
     */
    private function setBreadcrumb(&$breadcrumb, $count, $elements)
    {
        $breadcrumb[$count] = [
            'id' => $elements->getId(),
            'title' => $elements->getTitle(),
            'prepend' => $elements->getPrepend(),
            'link' => $this->url->get($elements->getPath()),
        ];
    }
}