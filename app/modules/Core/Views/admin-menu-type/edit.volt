{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button type="submit" form="menuTypeForm" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Save
        </button>
        <button onclick="location.href='{{ url("admin/core/menu-type/index") }}'" class="btn btn-sm btn-danger">
            <i class='fa fa-remove'></i> Cancel
        </button>
    </div>
    <div class="box-body">
        {{ form.renderForm(
            url("admin/core/menu-type/save"),
            [
                'form': ['id': 'menuTypeForm'],
                'label': ['class': 'control-label']
            ]
        ) }}
    </div>
</div>