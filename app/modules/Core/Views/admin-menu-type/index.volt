{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button onclick="location.href='{{ url("admin/core/menu-type/create") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
            </ul>
        </div>
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