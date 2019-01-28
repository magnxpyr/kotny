{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/route/create") }}'" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'ordering', 'searchable': false],
                    ['data': 'id', 'searchable': false],
                    ['data': 'name'],
                    ['data': 'pattern'],
                    ['data': 'status']
                ],
                'url': url('admin/core/route/search'),
                'actions': [
                    'update': url('admin/core/route/edit'),
                    'delete': url('admin/core/route/delete')
                ],
                'order': [[1, 'asc']],
                'options': [
                    'rowReorder': [
                        'dataSrc': 'ordering',
                        'selector': '.reorder',
                        'snapX': true,
                        'update': false
                    ]
                ],
                'columnDefs': [
                    ['orderable': true, 'className': 'reorder', 'targets': 1],
                    ['orderable': false, 'targets': '_all']
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>

{% do addViewJs('admin-route/index-scripts') %}