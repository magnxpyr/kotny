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
            {{ form.label('title', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("title") }}
            </div>
        </div>

        <div class="form-group">
            {{ form.label('type', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("type") }}
            </div>
        </div>

        <div class="form-group" id="path-group">
            {{ form.label('path', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("path") }}
            </div>
        </div>

        <div class="form-group" id="link-group" style="display: none">
            {{ form.label('link', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("link") }}
            </div>
        </div>

        <div class="form-group">
            {{ form.label('status', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("status") }}
            </div>
        </div>

        <div class="form-group">
            {{ form.label('role_id', ['class': 'control-label col-sm-2']) }}
            <div class="input-group">
                {{ form.render("role_id") }}
            </div>
        </div>
    </div>
    {{ end_form() }}
</div>