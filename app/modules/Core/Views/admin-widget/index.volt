{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="location.href='{{ url("admin/core/widget/create") }}'" class="btn btn-sm btn-primary">
            <i class='fa fa-plus'></i> New
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
        [
        'columns': [
        ['data': 'id', 'searchable': false],
        ['data': 'ordering'],
        ['data': 'title'],
        ['data': 'package'],
        ['data': 'position'],
        ['data': 'viewLevel'],
        ['data': 'publish_up'],
        ['data': 'status']
        ],
        'order': '[[2, "ASC"]]',
        'columnDefs': '[
                    {
                        "targets": 7,
                        "render": $.fn.dataTable.render.moment("X", "DD-MM-YYYY")
                    }
                ]',
        'url': url('admin/core/widget/search'),
        'actions': [
        'update': url('admin/core/widget/edit'),
        'delete': url('admin/core/widget/delete')
        ],
        'tableId': 'table'
        ],
        ['cache': false]
        ) }}
    </div>
</div>