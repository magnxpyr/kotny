<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
        $model = $this->modelsManager->createBuilder()
            ->columns('widget.*, package.*')
            ->addFrom('Module\Core\Models\Widget', 'widget')
            ->addFrom('Module\Core\Models\Package', 'package')
            ->andWhere('widget.package_id = package.id')
            ->andWhere('widget.position = :position:', ['position' => $section])
            ->orderBy('widget.ordering')
            ->getQuery()
            ->execute();

        $html = null;
        foreach ($model as $row) {
            $html .= $this->widget->render([
               'widget' => $row->package->name,
               'controller' => 'controller',
            ], (array) json_decode($row->widget->params));
        }

        return $html;
    }
}