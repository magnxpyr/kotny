<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\SortableView\Controllers;

/**
 * Class Controller
 * @package Widget\GridView\Controllers
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * Render GridView
     */
    public function indexAction()
    {
        $this->assets->collection('footer-js')
            ->addJs('https://cdnjs.cloudflare.com/ajax/libs/nestedSortable/2.0.0/jquery.mjs.nestedSortable.min.js');

        // disable view render
        $this->setRenderView(false);

        // render our content
        $this->generateHtml();
    }

    /**
     * Generate Table Html
     */
    private function generateHtml()
    {
        $model = $this->getParam('model');
        if (!$model) {
            return;
        }
        $level = $this->getParam("level", 1);
        $colSize = $this->getParam('colSize');
        $actions = $this->getParam('actions');

        echo '<div class="row list-item">';
        foreach ($this->getParam('header') as $key => $header) {
            echo "<div class='col col-xs-$colSize[$key]'>$header</div>";
        }
        if ($actions) {
            $colSizeNext = 1;
            $countSize = count($colSize);
            if (isset($colSize[$countSize - 1])) $colSizeNext = $colSize[$countSize - 1];
            echo "<div class='col-xs-$colSizeNext'>Actions</div>";
        }
        echo "</div>";

        echo '<ol class="sortable" id="' . $this->getParam('tableId', 'root_menu') . '">';

        foreach ($model as $m => $value) {
            if ($value->level == $level) {
                echo "</li>\n";
            } else if ($value->level > $level) {
                echo "<ol>\n";
            } else {
                echo "</li>\n";

                for ($i = $level - $value->level; $i; $i--) {
                    echo "</ol>\n";
                    echo "</li>\n";
                }
            }

            echo "<li id='item_$value->id'><div class='list-item'>\n";

            foreach ($this->getParam('data') as $key => $data) {
                echo "<div class='col col-xs-$colSize[$key]'>";
                if ($key == 0) echo "<i class='fa fa-reorder'></i>";
                echo $this->getValue($value, $data) . "</div>";
            }
            if ($this->getParam('actions')) {
                $colSizeNext = 1;
                if (isset($colSize[$key + 1])) $colSizeNext = $colSize[$key + 1];
                echo "<div class='col col-xs-$colSizeNext'>";
                echo '<a href="' . $actions['update'] . "/" . $value->getId() . '"><i class="fa fa-edit"></i></a>';
                $url = $actions['delete'] . "/" . $value->getId();
                echo "<a href='#' class='ajaxDelete' data-url='$url' data-parent-id='#item_" . $value->getId() . "'><i class='fa fa-trash-o'></i></a></div></div>";
            }

            $level = $value->level;
        }

        for ($i = $level; $i; $i--) {
            echo "</li>\n";
            echo "</ol>\n";
        }

        echo '</ol>';
    }

    private function getValue($model, $data) {
        if (is_callable($data)) {
            return $data($model);
        }
        return $data;
    }
}