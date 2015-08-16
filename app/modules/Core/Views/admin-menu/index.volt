{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button onclick="location.href='{{ url("admin/core/menu/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-success"><i class="fa fa-check"></i> Publish</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-danger"><i class="fa fa-remove"></i> Unpublish</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-warning"><i class="fa fa-trash"></i> Trash</button></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <div class="dataTables_filter">
                {{ form("menu/search", "method":"post", "autocomplete": "off", "class": "form-inline") }}
                <fieldset>
                    <div class="form-group">
                        <div class="input-group">
                            {{ text_field("menu_type_id", "type": "numeric", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("type", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("title", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("status", "type": "numeric", "class": "form-control input-sm") }}
                        </div>
                    </div>
                </fieldset>
                {{ end_form() }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="dataTables_wrapper form-inline dt-bootstrap">
            <!-- Table -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Id</th>
                    </tr>
                </thead>
                <tbody>
                    {% if page.items is defined %}
                        {% for item in page.items %}
                            <tr>
                                <td>{{ item.getTitle() }}</td>
                                <td>{{ item.getStatus() }}</td>
                                <td>{{ item.getRoleId() }}</td>
                                <td>{{ item.getId() }}</td>
                                <td>{{ link_to("admin/core/menu/edit/"~item.getId(), "Edit") }}</td>
                                <td>{{ link_to("admin/core/menu/delete/"~item.getId(), "Delete") }}</td>
                            </tr>
                        {% endfor %}
                    {% endif %}
                </tbody>
            </table>

            <?php
            echo '<ol class="sortable">';
            $level=0;

            foreach($menu as $n=>$category)
            {
                if($category->level==$level)
                echo "</li>\n";
                else if($category->level>$level)
                echo "<ol>\n";
                    else
                    {
                        echo "</li>\n";

                        for($i=$level-$category->level;$i;$i--)
                        {
                        echo "</ol>\n";
                    echo "</li>\n";
                    }
                }

                echo "<li>\n";
                echo $category->getTitle();
                echo $category->getStatus();
                echo $category->getRoleId();
                echo $category->getId();
                echo $this->tag->linkTo("admin/core/menu/edit/" . $category->getId(), '<i class="fa fa-edit"></i>');
                echo $this->tag->linkTo("admin/core/menu/delete/" . $category->getId(), '<i class="fa fa-trash-o"></i>');
                $level=$category->level;
            }

            for($i=$level;$i;$i--)
            {
                echo "</li>\n";
                echo "</ol>\n";
            }

            echo '</ol>';
            ?>
        </div>
    </div>
</div>

{%
do assets.addInlineJs('
    $(document).ready(function(){
        $("ol.sortable").nestedSortable({
       //     handle: "div",
            items: "li",
            isTree: true,
            tabSize: 5
        //    toleranceElement: "> div"
        });

        $("#toArray").click(function(e) {
            arraied = $("ol.sortable").nestedSortable("toArray", {startDepthCount: 0});
            arraied = dump(arraied);
            (typeof($("#toArrayOutput")[0].textContent) != "undefined") ?
                $("#toArrayOutput")[0].textContent = arraied : $("#toArrayOutput")[0].innerText = arraied;
        });
    });')
%}