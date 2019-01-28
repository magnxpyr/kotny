<div class="box box-default">
    <div class="box-header with-border">
        <button onclick="flushCache()" class="btn btn-sm btn-primary">
            <i class="fa fa-close"></i> Flush cache
        </button>
        <button onclick="flushVoltCache()" class="btn btn-sm btn-primary">
        <i class="fa fa-close"></i> Flush volt cache
        </button>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id']
                ],
                'url': url('admin/core/cache/search'),
                'actions': [
                    'delete': url('admin/core/cache/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>
{% do addViewJs("admin-cache/index-scripts") %}