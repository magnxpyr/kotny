{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <ul class="nav nav-pills">
            <li><button onclick="location.href='{{ url("admin/core/menu/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
        </ul>
    </div>

    <div class="box-body">
        <div class="row list-item">
            <div class="col-xs-4">Title</div>
            <div class="col-xs-2">Status</div>
            <div class="col-xs-2">Role</div>
            <div class="col-xs-2">Id</div>
        </div>

        <?php
        echo '<ol class="sortable ui-sortable sortable-list">';
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

            echo "<li><div class='list-item'>\n";
            echo '<div class="col-xs-4"><i class="fa fa-reorder"></i>' . $category->getTitle() . '</div>';
            echo '<div class="col-xs-2">' . $this->helper->getArticleStatus($category->getStatus()) . '</div>';
            echo '<div class="col-xs-2">' . $this->helper->getUserRole($category->getRoleId()) . '</div>';
            echo '<div class="col-xs-2">' . $category->getId() . '</div>';
            echo '<div class="col-xs-2">' . $this->tag->linkTo("admin/core/menu/edit/" . $category->getId(), '<i class="fa fa-edit"></i>');
            echo $this->tag->linkTo("admin/core/menu/delete/" . $category->getId(), '<i class="fa fa-trash-o"></i>') . '</div></div>';
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

{%
do assets.addInlineJs('
    $(document).ready(function(){
        $("ol.sortable").nestedSortable({
            handle: "i.fa-reorder",
            items: "li",
            isTree: true,
            forcePlaceholderSize: true,
			helper:	"clone",
			opacity: .6,
			placeholder: "placeholder",
			revert: 250,
			tabSize: 25,
			tolerance: "pointer",
			toleranceElement: "> div",
			isTree: true,
			expandOnHover: 700,
			startCollapsed: true
        });

        $("#toArray").click(function(e) {
            arraied = $("ol.sortable").nestedSortable("toArray", {startDepthCount: 0});
            arraied = dump(arraied);
            (typeof($("#toArrayOutput")[0].textContent) != "undefined") ?
                $("#toArrayOutput")[0].textContent = arraied : $("#toArrayOutput")[0].innerText = arraied;
        });
    });')
%}