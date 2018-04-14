{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/role/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'name'],
                    ['data': 'parent_id'],
                    ['data': 'description']
                ],
                'url': url('admin/core/role/search'),
                'actions': [
                    'update': url('admin/core/role/edit'),
                    'delete': url('admin/core/role/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>