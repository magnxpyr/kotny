{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button type="submit" form="viewLevelForm" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Save
        </button>
        <button onclick="location.href='{{ url("admin/core/view-level/index") }}'" class="btn btn-sm btn-danger">
            <i class='fa fa-remove'></i> Cancel
        </button>
    </div>
    <div class="box-body view-level">
        {{ form.renderForm(
            url("admin/core/view-level/save"),
            [
                'form': ['id': 'viewLevelForm'],
                'label': ['class': 'control-label']
            ]
        ) }}
    </div>
</div>