{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button type="submit" form="menuTypeForm" class="btn btn-sm btn-block btn-success"><i class="glyphicon glyphicon-edit"></i> Save</button></li>
                <li><button onclick="location.href='{{ url("admin/core/menu-type/index") }}'" class="btn btn-sm btn-block btn-danger"><i class='glyphicon glyphicon-remove-circle'></i> Cancel</button></li>
            </ul>
        </div>
    </div>
    {{ form("admin/core/menu-type/save", "method":"post", "id":"menuTypeForm") }}
    <div class="box-body">
        {{ form.render("id") }}
        <div class="form-group">
            {{ form.label("title", ["class": "control-label col-sm-2"]) }}
            <div class="input-group">
                {{ form.render("title") }}
            </div>
        </div>
        <div class="form-group">
            {{ form.label("role_id", ["class": "control-label col-sm-2"]) }}
            <div class="input-group">
                {{ form.render("role_id") }}
            </div>
        </div>
        <div class="form-group">
            {{ form.label("description", ["class": "control-label col-sm-2"]) }}
            <div class="input-group">
                {{ form.render("description") }}
            </div>
        </div>
    </div>
    {{ end_form() }}
</div>