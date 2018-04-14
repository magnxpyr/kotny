{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/menu-type/create") }}'" class="btn btn-sm btn-primary">
            <i class='fa fa-plus'></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'title']
                ],
                'url': url('admin/core/menu-type/search'),
                'actions': [
                    'update': url('admin/core/menu-type/edit'),
                    'delete': url('admin/core/menu-type/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>