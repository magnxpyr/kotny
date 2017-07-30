{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            {#<ul class="nav nav-pills">#}
                {#<li><button onclick="location.href='{{ url("admin/core/widget/create") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>#}
            {#</ul>#}
        </div>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'name'],
                    ['data': 'version'],
                    ['data': 'author'],
                    ['data': 'website'],
                    ['data': 'status']
                ],
                'url': url('admin/core/module/search'),
                'actions': [
                    'update': url('admin/core/module/edit'),
                    'delete': url('admin/core/package-manager/remove-package/module')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>