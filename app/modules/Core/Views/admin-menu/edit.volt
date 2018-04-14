{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button type="submit" form="menuForm" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Save
        </button>
        <button onclick="location.href='{{ url("admin/core/menu/index/") }}' + $('#menu_type_id').val()" class="btn btn-sm btn-danger">
            <i class='fa fa-remove'></i> Cancel
        </button>
    </div>
    <div class="box-body">
        {{ form.renderForm(
            url("admin/core/menu/save"),
            [
                'form': ['id': 'menuForm'],
                'label': ['class': 'control-label col-sm-2']
            ]
        ) }}
    </div>
</div>