<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\SortableView;

use Core\Models\Menu;
use Phalcon\Db\Column;

/**
 * Class Controller
 * @package Widget\GridView
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * Render GridView
     */
    public function indexAction()
    {
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
        $level = $this->getParam("level", 1);
        $colSize = $this->getParam('colSize');
        $actions = $this->getParam('actions');

        echo '<div class="row list-item">';
        foreach ($this->getParam('header') as $key => $header) {
            echo "<div class='col-xs-$colSize[$key]'>$header</div>";
        }
        if ($actions) {
            $colSizeNext = 1;
            $countSize = count($colSize);
            if (isset($colSize[$countSize])) $colSizeNext = $colSize[$countSize];
            echo "<div class='col-xs-$colSizeNext'>Actions</div>";
        }
        echo "</div>";

        echo '<ol class="sortable" id="' . $this->getParam('tableId', 'root_menu') . '">';

        foreach ($this->getParam('model') as $m => $model) {
            if ($model->level == $level) {
                echo "</li>\n";
            } else if ($model->level > $level) {
                echo "<ol>\n";
            } else {
                echo "</li>\n";

                for ($i = $level - $model->level; $i; $i--) {
                    echo "</ol>\n";
                    echo "</li>\n";
                }
            }

            echo "<li id='item_$model->id'><div class='list-item'>\n";

            foreach ($this->getParam('data') as $key => $data) {
                echo "<div class='col-xs-$colSize[$key]'>";
                if ($key == 0) echo "<i class='fa fa-reorder'></i>";
                echo $this->getValue($model, $data). "</div>";
            }
            if ($this->getParam('actions')) {
                $colSizeNext = 1;
                if (isset($colSize[$key + 1])) $colSizeNext = $colSize[$key + 1];
                echo "<div class='col-xs-$colSizeNext'>";
                echo $this->tag->linkTo($actions['update'] . "/" . $model->getId(), '<i class="fa fa-edit"></i>');
                $url = $actions['delete'] . "/" . $model->getId();
                echo "<a href='#' class='ajaxDelete' data-url='$url' data-parent-id='#item_" . $model->getId(). "'><i class='fa fa-trash-o'></i></a></div></div>";
            }

            $level = $model->level;
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