{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/user/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'username'],
                    ['data': 'email'],
                    ['data': 'name'],
                    ['data': 'status']
                ],
                'url': url('admin/core/user/search'),
                'actions': [
                    'update': url('admin/core/user/edit'),
                    'delete': url('admin/core/user/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>