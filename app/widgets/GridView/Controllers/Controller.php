<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\GridView\Controllers;

use Phalcon\Text;

/**
 * Class Controller
 * @package Widget\GridView\Controllers
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
        echo '<button id="searchFilter" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>Search Filter</button>';
        echo '<div class="searchContainer" style="display: none">' . $this->searchHtml . '</div><br>';
        echo '<div class="table-responsive"></div><table id="'.$this->getParam('tableId').'" class="table table-striped table-bordered dataTable no-footer" width="100%"><thead><tr>';
        echo $this->headHtml;
        echo '</tr></thead><tbody></tbody></table></div>';
    }

    /**
     * Generate inline JS
     */
    private function getJs()
    {
        $js = 'var table = $("#'.$this->getParam('tableId').'").DataTable({
            serverSide: true,
            responsive: {
                details: {
                    type: "column",
                    target: -1
                }
            },
            ajax: {
                url: "' . $this->getParam('url') . '",
                method: "POST",
                deferRender: true,
                headers: { "X-CSRF-Token": "' . $this->tokenManager->getToken() . '" }
            },';

        if ($this->getParam('order')) {
            $js .= 'order: '. $this->getParam('order') . ',';
        } else {
            $js .= 'order: [[1, "desc"]],';
        }

        $js .= 'columns: [{
            data: null,
            defaultContent: "",
            className: "select-checkbox",
            orderable: false,
            searchable: false
        },'.$this->js;

        if ($this->getParam('actions')) {
            $js .= '
            {
                data: null,
                defaultContent: "",
                orderable: false,
                searchable: false,
                className: "all",
                render: function (data, type, row) {
                console.log(data);
                    var custom = "";
                ';


            if (isset($this->getParam('actions')['custom'])) {
                foreach ($this->getParam('actions')['custom'] as $action) {
                    if (isset($action['conditional'])) {
                        $js .= 'if (' . $action['conditional'] . ') {';
                    }
                    $js .= 'custom += "<li>' . $action['action'] . '</li>"';
                    if (isset($action['conditional'])) {
                        $js .= '}';
                    }
                }
            }

            $js .= 'return "<ul class=\"datatable-action\">';

            if (isset($this->getParam('actions')['update'])) {
                $js .= '<li><a href=\"'.$this->getParam('actions')['update'].'/"+data.DT_RowId+"\"><i class=\"fa fa-edit\"></i></a></li>';
            }
            if (isset($this->getParam('actions')['delete'])) {
                $js .= '<li><a href=\"#\" class=\"ajaxDelete\" data-url=\"'.$this->getParam('actions')['delete'].'/" + data.id +
                    "\" data-parent-id=\"#"+data.id+"\"><i class=\"fa fa-trash-o\"></i></a></li>';
            }

            $js .= '" + custom}}';
        }

        $js .= ',{ 
                data: null,
                defaultContent: "",
                className: "control",
                orderable: false,
                searchable: false
            }],
        select: {
            style: "multi",
            selector: "td:first-child"
        }';

        if ($this->getParam('options')) {
            $js .= "," . $this->getParam('options');
        }

        $js .= ', columnDefs: [ {
                    className: "control",
                    orderable: false,
                    targets:   -1
                }';

        if ($this->getParam('columnDefs')) {
            $js .= ',' . trim($this->getParam('columnDefs'), '[]');
        }
        $js .= ']';

        // Apply the search
        $js .= '});
        
        table.columns().eq(0).each(function( colIdx ) {
            $( "input[data-col=" + colIdx + "]", $(".searchContainer") ).on( "keyup change", function () {
                if (colIdx !== 0) {table
                    .column( colIdx )
                    .search( this.value, true, false )
                    .draw();
            } 
            } );$("input[data-col=" +colIdx+ "]", $(".searchContainer")).on("click", function(e) {
                if (colIdx !== 0) {e.stopPropagation();}
            });
        });
        
        table.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                table.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                table.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        });
        
        $("#searchFilter").click(function(){
            $(".searchContainer").slideToggle();
        });';

        $this->assets->addInlineJs($js);
    }

    /**
     * Generate table head and js
     */
    private function getHtml()
    {
        $this->headHtml .= '<th></th>';
        $this->searchHtml .= '<th></th>';
        if (!empty($this->getParam('columns'))) {
            foreach ($this->getParam('columns') as $key => $column) {
                $this->headHtml .= '<th data-column-id="'.$column['data'].'">'.ucfirst(Text::camelize($column['data'])).'</th>';
                $this->searchHtml .= '<th></th>';

                if (!isset($column['searchable']) || $column['searchable']) {
                    $this->searchHtml .= '
                            <div class="col-md-4 col-sm-6">
                                <input type="text" data-col="' . ($key + 1) . '" placeholder="Search '.Text::camelize($column['data']).'">
                            </div>
                        ';
                } else {
                    $this->searchHtml .= '<th></th>';
                }

                $this->js .= '{data: "'.$column['data'].'"';
                if(isset($column['searchable'])) $this->js .= ', searchable: "' .$column['searchable'].'"';
                $this->js .=  '},';
            }
        }
        $this->headHtml .= '<th data-column-id="actions">Actions</th><th class="control" style="display: none;"></th>';
        $this->searchHtml .= '<th></th>';

    }
}