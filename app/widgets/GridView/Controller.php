<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\GridView;

use Core\Models\Menu;
use Phalcon\Db\Column;

/**
 * Class Controller
 * @package Widget\GridView
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * @var string
     */
    private $headHtml;

    /**
     * @var string
     */
    private $searchHtml;

    /**
     * @var string
     */
    private $js;

    /**
     * Render GridView
     */
    public function indexAction()
    {
        // disable view render
        $this->setRenderView(false);

        // render our content
        $this->getHtml();
        $this->getTableHtml();
        $this->getJs();
    }

    /**
     * Generate Table Html
     */
    private function getTableHtml()
    {
        echo '<table id="'.$this->getParam('tableId').'" class="table table-condensed table-hover table-striped"><thead><tr>';
        echo $this->headHtml;
        echo '</tr><tr>';
        echo $this->searchHtml;
        echo '</tr></thead><tbody></tbody></table>';
    }

    /**
     * Generate inline JS
     */
    private function getJs()
    {
        $js = 'var table = $("#'.$this->getParam('tableId').'").DataTable({
            serverSide: true,
            ajax: {
                url: "'.$this->getParam('url').'",
                method: "POST",
                deferRender: true
            },
            order: [[1, "desc"]],
            columns: [{
                data: null,
                defaultContent: "",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return "<input type=\"checkbox\" name=\"id[]\" value=\"\">";
                }
            },'.$this->js;

        if ($this->getParam('actions')) {
            $js .= '
            {
                data: null,
                defaultContent: "",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return "<a href=\"'.$this->getParam('actions')['update'].'/"+data.DT_RowId+"\"><i class=\"fa fa-edit\"></i></a>" +
                        "<a href=\"#\" class=\"ajaxDelete\" data-url=\"'.$this->getParam('actions')['delete'].'/" + data.DT_RowId +
                        "\" data-parent-id=\"#"+data.DT_RowId+"\"><i class=\"fa fa-trash-o\"></i></a>";
                }
            }';
        }

        $js .= ']';

        if ($this->getParam('options')) {
            $js .= "," . $this->getParam('options');
        }

        // Apply the search
        $js .='});
        table.columns().eq(0).each(function( colIdx ) {
            $( "input", table.column( colIdx ).header() ).on( "keyup change", function () {
                table
                    .column( colIdx )
                    .search( this.value, true, false )
                    .draw();
            } );
            $("input", table.column(colIdx).header()).on("click", function(e) {
                e.stopPropagation();
            });
        });';

        $this->assets->addInlineJs($js);
    }

    /**
     * Generate table head and js
     */
    private function getHtml()
    {
        $this->headHtml .= '<th data-column-id="select-all-checkboxes"><input type="checkbox" name="select-all" value="1" id="select-all"></th>';
        $this->searchHtml .= '<th></th>';
        foreach ($this->getParam('columns') as $column) {
            $this->headHtml .= '<th data-column-id="'.$column['data'].'">'.ucfirst($column['data']).'</th>';

            if (!isset($column['searchable']) || $column['searchable']) {
                $this->searchHtml .= '<th class="dataTables_searchable" rowspan="1" colspan="1">
                        <input type="text" placeholder="Search '.$column['data'].'">
                    </th>';
            } else {
                $this->searchHtml .= '<th></th>';
            }

            $this->js .= '{data: "'.$column['data'].'"';
            if(isset($column['searchable'])) $this->js .= ', searchable: "' .$column['searchable'].'"';
            $this->js .=  '},';
        }
        $this->headHtml .= '<th data-column-id="actions">Actions</th>';
        $this->searchHtml .= '<th></th>';
    }
}