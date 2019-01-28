<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\View;

use Engine\Di\Injectable;
use Engine\Widget\Widget;

/**
 * Class Section
 * @package Engine\Mvc\View
 */
class Section extends Injectable
{
    const CACHE_KEY_PREFIX = "section_";
    /**
     * Render widgets from a section
     *
     * @param $section string - Section name
     * @return string
     */
    public function render($section)
    {
        $time = time();
        $model = $this->modelsManager->createBuilder()
            ->columns('widget.*, package.*, viewLevel.*')
            ->addFrom('Module\Core\Models\Widget', 'widget')
            ->addFrom('Module\Core\Models\Package', 'package')
            ->addFrom('Module\Core\Models\ViewLevel', 'viewLevel')
            ->andWhere('widget.package_id = package.id')
            ->andWhere('widget.publish_up <= ' . $time)
            ->andWhere('widget.publish_down is null')
            ->orWhere('widget.publish_down > ' . $time)
            ->andWhere('widget.status = 1')
            ->andWhere('widget.position = :position:', ['position' => $section])
            ->andWhere('widget.view_level = viewLevel.id')
            ->orderBy('widget.ordering')
            ->getQuery()
            ->cache(['key' => self::CACHE_KEY_PREFIX . $section, 'lifetime' => DEV ? 1 : Widget::CACHE_LIFETIME])
            ->execute();

        $html = null;
        foreach ($model as $row) {
            if (!$this->acl->checkViewLevel($row->viewLevel->getRolesArray())) continue;

            $params = (array) json_decode($row->widget->params);
            $params['title'] = $row->widget->title;
            $params['showTitle'] = $row->widget->showTitle;

            $html .= $this->widget->render([
               'widget' => $row->package->name,
               'controller' => 'controller',
            ], $params, [
                'cache' => DEV ? null : $row->widget->cache,
                'layout' => $row->widget->layout,
                'view' => $row->widget->view
            ]);
        }

        return $html;
    }
}