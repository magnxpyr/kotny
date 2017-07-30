{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/menu/create") }}'" class="btn btn-sm btn-block btn-primary">
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
        {{ widget.render("SortableView",
            [
                'model': model,
                'actions': [
                    'update': url('admin/core/menu/edit'),
                    'delete': url('admin/core/menu/delete')
                ],
                'header': ['Title', 'Status', 'View Level', 'Id'],
                'data': [
                    f('$model->getTitle()'),
                    f('$this->helper->getArticleStatus($model->getStatus())'),
                    f('$model->viewLevel->getName()'),
                    f('$model->getId()')
                ],
                'colSize': [4, 2, 2, 2, 2]
            ]
        ) }}
    </div>
</div>