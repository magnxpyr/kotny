{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/view-level/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'name']
                ],
                'url': url('admin/core/view-level/search'),
                'actions': [
                    'update': url('admin/core/view-level/edit'),
                    'delete': url('admin/core/view-level/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>