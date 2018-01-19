<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\View;

use Engine\Di\Injectable;

/**
 * Class Section
 * @package Engine\Mvc\View
 */
class Section extends Injectable
{
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
            ->execute();

        $html = null;
        foreach ($model as $row) {
            if (!$this->acl->checkViewLevel($row->viewLevel->getRolesArray())) continue;

            $params = (array) json_decode($row->widget->params);
            $params['widget'] = $row->widget->title;

            $html .= $this->widget->render([
               'widget' => $row->package->name,
               'controller' => 'controller',
            ], $params);
        }

        return $html;
    }
}