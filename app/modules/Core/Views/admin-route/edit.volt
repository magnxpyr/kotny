{{ content() }}
<form method='post' action="{{ url("admin/core/route/save") }}" id="userForm">
    <div class="box box-default">
        <div class="box-header with-border">
            <button type="submit" form="userForm" class="btn btn-sm btn-success">
                <i class="fa fa-edit"></i> Save
            </button>
            <button type="button" onclick="location.href='{{ url("admin/core/route/index") }}'" class="btn btn-sm btn-danger">
                <i class='fa fa-remove'></i> Cancel
            </button>
        </div>
        <div class="box-body">
            {{ form.render('id') }}
            {{ form.render('csrf') }}

            <div class="form-group" id="wrapper-title">
                {{ form.render('pattern', ['placeholder': t._('Pattern'), 'class': 'form-control input-lg']) }}
            </div>
            <div class="form-group" id="wrapper-title">
                {{ form.render('name', ['placeholder': t._('Name'), 'class': 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    {{ form.renderDecorated('package_id', ['label': ['class': 'control-label']]) }}
                    <div class="row">
                        <div class="col-md-6">
                            {{ form.renderDecorated('controller', ['label': ['class': 'control-label']]) }}
                        </div>
                        <div class="col-md-6">
                            {{ form.renderDecorated('action', ['label': ['class': 'control-label']]) }}
                        </div>
                    </div>
                    <div>
                    <label for="_paramKey" class="control-label">Params</label>
                    <div id="wrapper-params">
                        {% if form.getValue('params') is empty %}
                            <div class="input-group input-wrapper" id="wrapper-params0">
                                <span class="input-group-addon delete" onclick="deleteParamInput('wrapper-params0')">
                                    <i class="fa fa-minus"></i>
                                </span>
                                <input type="text" id="_paramKey0" name="_paramKey[]" value="" class="form-control img-path-input"/>
                                <span class="input-group-addon" data-input="_paramKey0">:</span>
                                <input type="text" id="_paramValue0" name="_paramValue[]" value="" class="form-control img-path-input"/>
                            </div>
                        {% else %}
                            {% for index, param in form.getValue('params') %}
                                <div class="input-group input-wrapper" id="wrapper-params{{ index }}">
                                    <span class="input-group-addon delete" onclick="deleteParamInput('wrapper-params{{ index }}')">
                                        <i class="fa fa-minus"></i>
                                    </span>
                                    <input type="text" id="_paramKey{{ index }}" name="_paramKey[]" value="{{ index }}" class="form-control img-path-input"/>
                                    <span class="input-group-addon" data-input="_paramKey{{ index }}">:</span>
                                    <input type="text" id="_paramValue{{ index }}" name="_paramValue[]" value="{{ param }}" class="form-control img-path-input"/>
                                </div>
                            {% endfor %}
                        {% endif %}
                    </div>
                    <div class="input-group">
                        <button class="btn btn-primary" onclick="addParamInput()"><i class="fa fa-plus"></i> {{ t._('Add Param') }}</button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                    {{ form.renderDecorated('method', ['label': ['class': 'control-label']]) }}
                </div>
            </div>
        </div>
    </div>
</form>

{% do addViewJs('admin-route/edit-scripts') %}