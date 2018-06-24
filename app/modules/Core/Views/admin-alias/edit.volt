{{ content() }}
<form method='post' action="{{ url("admin/core/alias/save") }}" id="userForm">
    <div class="box box-default">
        <div class="box-header with-border">
            <button type="submit" form="userForm" class="btn btn-sm btn-success">
                <i class="fa fa-edit"></i> Save
            </button>
            <button type="button" onclick="location.href='{{ url("admin/core/alias/index") }}'" class="btn btn-sm btn-danger">
                <i class='fa fa-remove'></i> Cancel
            </button>
        </div>
        <div class="box-body">
            {{ form.render('id') }}
            {{ form.render('csrf') }}

            <div class="form-group" id="wrapper-title">
                <div class="input-group">
                    <span class="input-group-addon round-left gray-bg">{{ url('/') }}</span>
                    {{ form.render('url', ['placeholder': t._('Custom Url'), 'class': 'form-control input-lg straight-left']) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    {{ form.renderDecorated('route_id', ['label': ['class': 'control-label']]) }}
                </div>
                <div class="col-md-3">
                    {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div>
                        <label for="_paramKey" class="control-label">Params</label>
                        <div id="wrapper-params">
                            {{ params }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{% do addViewJs('admin-alias/edit-scripts') %}