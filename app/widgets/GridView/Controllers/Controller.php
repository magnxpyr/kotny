<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
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
        // render our content
        $this->getHtml();
        $this->viewWidget->setVars([
            'tableHtml' => $this->getTableHtml(),
            'js' => $this->getJs()
        ]);
    }

    /**
     * Generate Table Html
     */
    private function getTableHtml()
    {
            $ar = '<div class="searchFilterBtn"><button id="searchFilter" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Advanced Search</button></div>';
        $ar .= '<div id="searchFilterContainer" class="row" style="display: none">' . $this->searchHtml . '</div>';
        $ar .= '<div class="table-responsive"></div><table id="'.$this->getParam('tableId').'" class="table table-striped table-bordered dataTable no-footer" width="100%"><thead><tr>';
        $ar .= $this->headHtml;
        $ar .= '</tr></thead><tbody></tbody></table></div>';
        return $ar;
    }

    /**
     * Generate inline JS
     */
    private function getJs()
    {
        $params = $this->getParams();


        $js = 'var table = $("#'.$this->getParam('tableId').'").DataTable({
            
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

        $serverSide = isset($params['serverSide']) ? $params['serverSide'] : true;
        if ($serverSide) {
            $js .= 'serverSide: true,';
        }

        if (isset($params['order'])) {
            if (empty($params['order'])) {
                $js .= 'order: [],';
            } else {
                $js .= 'order: ['. $this->assocArrayToString($this->getParam('order')) . '],';
            }
        } else {
            $js .= 'order: [[1, "desc"]],';
        }

        $js .= 'columns: [
        {
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
                className: "all dt-action-wrapper",
                render: function (data, type, row) {
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
            $js .= "," . $this->arrayToString($this->getParam('options'));
        }

        $js .= ', columnDefs: [ {
                    className: "control",
                    orderable: false,
                    targets:   -1
                }';

        if ($this->getParam('columnDefs')) {
            $js .= ',' . $this->assocArrayToString($this->getParam('columnDefs'));
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
        
        $("#searchFilter").on("click", function(){
            $("#searchFilterContainer").slideToggle();
        });
        
        $(document).ready(function() {
            $("#searchFilterContainer").insertAfter("#table_wrapper .row:first-child")
            $(".searchFilterBtn").prependTo("#table_wrapper #table_filter")
        })
        ';

        return $js;
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
                            <div class="col-md-4 col-sm-6 form-group">
                                <div class="input-wrapper">
                                    <input class="form-control" type="text" data-col="' . ($key + 1) . '" placeholder="Search '.Text::camelize($column['data']).'">
                                </div>
                            </div>
                        ';
                } else {
                    $this->searchHtml .= '<th></th>';
                }

                $this->js .= '{' . $this->arrayToString($column) . '},';
            }
        }
        if ($this->getParam('actions')) {
            $this->headHtml .= '<th data-column-id="actions">Actions</th>';
        }
        $this->headHtml .= '<th class="control" style="display: none;"></th>';
        $this->searchHtml .= '<th></th>';

    }

    private function arrayToString($array)
    {
        $string = '';
        if (!$array) {
            $array = $this->getParam('options');
        }
        if ($array) {
            foreach ($array as $k => $v) {
                if (is_object($v) || is_array($v)) {
                    $string .= "'$k': {" . $this->arrayToString($v) . "},";
                } else {
                    if (is_bool($v)) {
                        $string .= "'$k': " . ($v ? 'true' : 'false') . ",";
                    } elseif ((is_numeric($v) && !is_string($v)) || $k == 'render') {
                        $string .= "'$k': $v,";
                    } else {
                        $string .= "'$k': '$v',";
                    }
                }
            }
        }
        return rtrim($string, ',');
    }

    private function assocArrayToString($array)
    {
        $string = '';
        if (!$array) {
            $array = $this->getParam('options');
        }
        if ($array) {
            foreach ($array as $v) {
                if (is_object($v) || is_array($v)) {
                    if (is_array($v) && !$this->isAssoc($v)) {
                        $string .= "[" . $this->assocArrayToString($v) . "],";
                    } else {
                        $string .= "{" . $this->arrayToString($v) . "},";
                    }
                } else {
                    $string .= "'$v',";
                }
            }
        }
        return rtrim($string, ',');
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}