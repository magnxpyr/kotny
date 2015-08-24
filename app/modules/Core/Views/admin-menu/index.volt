{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/menu/new") }}'" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-plus"></i> New</button>
                    </li>
                    <li>
                        <button data-url="{{ url("admin/core/menu/saveTree") }}" data-root="menu" class="btn btn-sm btn-block btn-success sortableSave">
                            <i class="fa fa-save"></i> Save</button>
                    </li>
                </ul>
            </div>
            <div class="col-sm-offset-3 col-md-offset-3  col-sm-3 col-md-3">
                {{ form("", "method": "post", "id": "menuForm") }}
                <div class="form-group">
                    {{ select("menuType", menuType, "using": ["id", "title"], "class": "form-control",
                        "onchange": "javascript: menuForm.submit()") }}
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="row list-item">
            <div class="col-xs-4">Title</div>
            <div class="col-xs-2">Status</div>
            <div class="col-xs-2">Role</div>
            <div class="col-xs-2">Id</div>
        </div>

        <?php
        echo '<ol class="sortable" id="root_menu">';
        $level = 1;

        foreach ($menu as $n => $category) {
            if ($category->level == $level) {
                echo "</li>\n";
            } else if($category->level>$level) {
                echo "<ol>\n";
            } else {
                echo "</li>\n";

                for ($i=$level-$category->level;$i;$i--) {
                    echo "</ol>\n";
                    echo "</li>\n";
                }
            }

            echo "<li id='item_$category->id'><div class='list-item'>\n";
            echo '<div class="col-xs-4"><i class="fa fa-reorder"></i>' . $category->getTitle() . '</div>';
            echo '<div class="col-xs-2">' . $this->helper->getArticleStatus($category->getStatus()) . '</div>';
            echo '<div class="col-xs-2">' . $this->helper->getUserRole($category->getRoleId()) . '</div>';
            echo '<div class="col-xs-2">' . $category->getId() . '</div>';
            echo '<div class="col-xs-2">' . $this->tag->linkTo("admin/core/menu/edit/" . $category->getId(), '<i class="fa fa-edit"></i>');
            $url = $this->url->get("admin/core/menu/delete/" . $category->getId());
            echo '<a href="#" class="ajaxDelete" data-url="'.$url.'" data-parent="'.$category->id.'"><i class="fa fa-trash-o"></i></a></div></div>';
            $level = $category->level;
        }

        for ($i = $level; $i; $i--) {
            echo "</li>\n";
            echo "</ol>\n";
        }

        echo '</ol>';
        ?>
    </div>
</div>