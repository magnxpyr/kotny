{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button type="submit" form="packageManagerForm" class="btn btn-sm btn-block btn-success"><i class="fa fa-edit"></i> Install</button></li>
            </ul>
        </div>
    </div>
    <div class="box-body">
        {{ form.renderForm(
            url("admin/core/package-manager/upload"),
            [
                'form': ['id': 'packageManagerForm', 'enctype': 'multipart/form-data'],
                'label': ['class': 'control-label col-sm-2']
            ]
        ) }}
    </div>
</div>