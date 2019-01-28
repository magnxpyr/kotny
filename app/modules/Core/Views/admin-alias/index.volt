{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/alias/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'url'],
                    ['data': 'route_name'],
                    ['data': 'status']
                ],
                'url': url('admin/core/alias/search'),
                'actions': [
                    'update': url('admin/core/alias/edit'),
                    'delete': url('admin/core/alias/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>