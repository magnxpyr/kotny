{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button type="submit" form="menuForm" class="btn btn-sm btn-block btn-success"><i class="glyphicon glyphicon-edit"></i> Save</button></li>
                <li><button onclick="location.href='{{ url("admin/core/menu/index") }}'" class="btn btn-sm btn-block btn-danger"><i class='glyphicon glyphicon-remove-circle'></i> Cancel</button></li>
            </ul>
        </div>
    </div>
    {{ form("admin/core/menu/save", "method":"post", "id":"menuForm") }}
    <div class="box-body">
        <div class="form-group">
            <label for="type" class="control-label col-sm-4">Type</label>
            <div class="input-group">
                {{ text_field("type", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="control-label col-sm-4">Title</label>
            <div class="input-group">
                {{ text_field("title", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="path" class="control-label col-sm-4">Path</label>
            <div class="input-group">
                {{ text_field("path", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="link" class="control-label col-sm-4">Link</label>
            <div class="input-group">
                {{ text_field("link", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label col-sm-4">Status</label>
            <div class="input-group">
                {{ text_field("status", "type": "numeric", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="parent_id" class="control-label col-sm-4">Parent</label>
            <div class="input-group">
                {{ text_field("parent_id",  "type": "numeric", "class": "form-control input-sm") }}
            </div>
        </div>

        <div class="form-group">
            <label for="role_id" class="control-label col-sm-4">Role</label>
            <div class="input-group">
                {{ text_field("role_id", "type": "numeric", "class": "form-control input-sm") }}
            </div>
        </div>
    </div>
    {{ end_form() }}
</div>