<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
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
        $this->setupAssets();
        $this->view->setVar('navigation', $this->setupNavigation());
        $this->view->setVar('sidebar_collapse', isset($_COOKIE['mg-sdbrClp']) && $_COOKIE['mg-sdbrClp'] == 1 ? 'sidebar-collapse' : '');
    }

    /**
     * Build Navigation Menu
     * @return array
     */
    protected function setupNavigation()
    {
        $isActive = false;
        $breadcrumb = [];
        $ids = ['currentId' => 0, 'previousId' => 0, 'parenId' => 0];
        $content = ['html' => '', 'breadcrumb' => ''];
        $count = 0;

        $uri = trim($this->router->getRewriteUri(), "/ ");

        $menuElements = Loader::fromResultset(Menu::find([
            'conditions' => 'menu_type_id = 1',
            'order' => 'lft'
        ]), 'viewLevel');
        $level = 1;

        if ($menuElements != null) {
            $isAnyActive = $this->isAnyActive($menuElements, $uri);
            foreach ($menuElements as $elements) {
                if (!$this->acl->checkViewLevel($elements->viewLevel->getRolesArray())) continue;

                $active = "";
                if (!$isActive) {
                    $this->generateBreadcrumbs($elements, $breadcrumb, $ids, $count);
                }

                if ($elements->getPath() != "#" && substr($elements->getPath(), 0, 4) != "http") {
                    $path = $this->url->get($elements->getPath());
                    if (!$isActive && $this->checkActive($isAnyActive, $uri, $elements->getPath())) {
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

    /**
     * Check if any menu is active
     *
     * @param $menuElements
     * @param $uri
     * @return bool
     */
    private function isAnyActive($menuElements, $uri)
    {
        $active = false;
        foreach ($menuElements as $menu) {
            if ($uri == trim($menu->getPath(), "/ ")) {
                $active = true;
                break;
            }
        }
        return $active;
    }

    private function checkActive($isAnyActive, $uri, $path)
    {
        $path = trim($path, "/ ");
        if ($isAnyActive) {
            return $uri == $path;
        } else {
            $uriArray = explode('/', $uri);
            $pathArray = explode('/', $path);
            if (sizeof($uriArray) > 3 && sizeof($pathArray) > 3) {
                return $uriArray[0] == $pathArray[0] && $uriArray[1] == $pathArray[1] && $uriArray[2] == $pathArray[2];
            } else {
                return false;
            }
        }
    }
}