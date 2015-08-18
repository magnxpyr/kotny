{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <ul class="nav nav-pills">
            <li><button onclick="location.href='{{ url("admin/core/menu/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
        </ul>
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
            echo '<ol class="sortable sortable-list">';
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
                echo '<div class="list-item col-xs-4">' . $category->getTitle() . '</div>';
                echo '<div class="list-item col-xs-2">' . $category->getStatus() . '</div>';
                echo '<div class="list-item col-xs-2">' . $category->getRoleId() . '</div>';
                echo '<div class="list-item col-xs-2">' . $category->getId() . '</div>';
                echo '<div class="list-item col-xs-2">' . $this->tag->linkTo("admin/core/menu/edit/" . $category->getId(), '<i class="fa fa-edit"></i>');
                echo $this->tag->linkTo("admin/core/menu/delete/" . $category->getId(), '<i class="fa fa-trash-o"></i>') . '</div>';
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