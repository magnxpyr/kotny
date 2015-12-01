{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/user/new") }}'" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-plus"></i> New</button>
                    </li>
                </ul>
            </div>
            <div class="col-sm-offset-3 col-md-offset-3  col-sm-3 col-md-3">

            </div>
        </div>
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
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>