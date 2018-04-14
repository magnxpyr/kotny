<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/category/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
        <button data-url="{{ url("admin/core/category/saveTree") }}" data-root="menu" class="btn btn-sm btn-success sortableSave">
            <i class="fa fa-save"></i> Save
        </button>
    </div>

    <div class="box-body">
        {{ widget.render("SortableView",
            [
                'model': model,
                'actions': [
                    'update': url('admin/core/category/edit'),
                    'delete': url('admin/core/category/delete')
                ],
                'header': ['Title', 'Status', 'View Level', 'Id'],
                'data': [
                    f('$model->getTitle()'),
                    f('$this->helper->getArticleStatus($model->getStatus())'),
                    f('$model->viewLevel->getName()'),
                    f('$model->getId()')
                ],
                'colSize': [5, 2, 2, 2, 1]
            ]
        ) }}
    </div>
</div>