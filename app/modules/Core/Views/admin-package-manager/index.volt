{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li>
                    <div class="box-body">
                    {{ form.renderForm(
                        url("admin/core/package-manager/upload"),
                        [
                            'form': ['id': 'packageManagerForm', 'enctype': 'multipart/form-data'],
                            'label': ['class': 'control-label col-sm-2']
                        ]
                    ) }}
                    </div>
                </li>
                <li>
                    <button type="submit" form="packageManagerForm" class="btn btn-sm btn-block btn-success">
                        <i class="fa fa-edit"></i> Install
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'name'],
                    ['data': 'type'],
                    ['data': 'version'],
                    ['data': 'author'],
                    ['data': 'website'],
                    ['data': 'status']
                ],
                'url': url('admin/core/package-manager/search'),
                'actions': [
                    'update': url('admin/core/package-manager/edit'),
                    'delete': url('admin/core/package-manager/remove-package/module')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>