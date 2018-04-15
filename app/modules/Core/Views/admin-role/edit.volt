{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button type="submit" form="roleForm" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Save
        </button>
        <button onclick="location.href='{{ url("admin/core/role/index") }}'" class="btn btn-sm btn-danger">
            <i class='fa fa-remove'></i> Cancel
        </button>
    </div>
    <div class="box-body">
        <form method='post' action="{{ url("admin/core/role/save") }}" id="userForm">
            {{ form.render('id') }}
            {{ form.render('csrf') }}

            <div class="row">
                <div class="col-sm-6">
                    {{ form.renderDecorated('name', ['label': ['class': 'control-label']]) }}
                </div>
                <div class="col-sm-6">
                    {{ form.renderDecorated('parent_id', ['label': ['class': 'control-label']]) }}
                </div>
            </div>

            {{ form.renderDecorated('description', ['label': ['class': 'control-label']]) }}

        </form>
    </div>
</div>