{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button type="submit" form="widgetForm" class="btn btn-sm btn-block btn-success"><i class="fa fa-edit"></i> Save</button></li>
                <li><button onclick="location.href='{{ url("admin/core/widget/index") }}'" class="btn btn-sm btn-block btn-danger"><i class='fa fa-remove'></i> Cancel</button></li>
            </ul>
        </div>
    </div>
    <div class="box-body">
        {{ form.renderForm(
            url("admin/core/widget/save"),
            [
                'form': ['id': 'widgetForm'],
                'label': ['class': 'control-label col-sm-2']
            ]
        ) }}
    </div>
</div>