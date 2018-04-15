{{ content() }}
<form method='post' action="{{ url("admin/core/user/save") }}" id="userForm">
    <div class="box box-default">
        <div class="box-header with-border">
            <button type="submit" form="userForm" class="btn btn-sm btn-success">
                <i class="fa fa-edit"></i> Save
            </button>
            <button type="button" onclick="location.href='{{ url("admin/core/user/index") }}'" class="btn btn-sm btn-danger">
                <i class='fa fa-remove'></i> Cancel
            </button>
        </div>
        <div class="box-body">
            {{ form.render('id') }}
            {{ form.render('csrf') }}

            <div class="row">
                <div class="col-sm-6">
                    {{ form.renderDecorated('username', ['label': ['class': 'control-label']]) }}
                </div>
                <div class="col-sm-6">
                    {{ form.renderDecorated('password', ['label': ['class': 'control-label']]) }}
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    {{ form.renderDecorated('email', ['label': ['class': 'control-label']]) }}
                </div>
                <div class="col-sm-6">
                    {{ form.renderDecorated('name', ['label': ['class': 'control-label']]) }}
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    {{ form.renderDecorated('role_id', ['label': ['class': 'control-label']]) }}
                </div>
                <div class="col-sm-6">
                    {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                </div>
            </div>
        </div>
    </div>
</form>