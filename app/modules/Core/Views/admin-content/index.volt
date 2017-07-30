<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/content/create") }}'" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-plus"></i> New</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'title'],
                    ['data': 'category'],
                    ['data': 'viewLevel'],
                    ['data': 'featured', 'searchable': false],
                    ['data': 'username'],
                    ['data': 'created_at'],
                    ['data': 'status', 'searchable': false],
                    ['data': 'hits', 'searchable': false]
                ],
                'columnDefs': '[
                    {
                        "targets": 7,
                        "render": $.fn.dataTable.render.moment("X", "DD-MM-YYYY")
                    }
                ]',
                'url': url('admin/core/content/search'),
                'actions': [
                    'update': url('admin/core/content/edit'),
                    'delete': url('admin/core/content/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>