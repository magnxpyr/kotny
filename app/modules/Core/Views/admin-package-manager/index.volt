{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="box-body upload-package">
            {{ form("admin/core/package-manager/upload", 'method': 'post') }}
            {{ form.render('csrf') }}
            {{ form.render("packageUpload") }}
            {{ end_form() }}

            <button type="submit" form="packageManagerForm" class="btn btn-sm btn-success install-btn">
                <i class="fa fa-upload"></i> Install
            </button>
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
                    'delete': url('admin/core/package-manager/remove-package'),
                    'custom': [{
                        'action': '<a href=\"'~url("admin/core/package-manager/install-package/")~'"+data.name+"/"+data.type+"\"><i class=\"fa fa-upload\"></i></a>',
                        'conditional': 'data.status === null'
                    }]
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>