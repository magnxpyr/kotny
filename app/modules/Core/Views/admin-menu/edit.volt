{{ content() }}
<div class="create edit-menu">
    <form method='post' action="{{ url("admin/core/menu/save") }}" id="menuForm">
        <div class="row">
            <div class="col-md-9">

                <div class="box box-default">
                    <div class="box-header with-border create__btnWrapper">
                        <button type="submit" form="menuForm" class="btn btn-sm btn-success">
                            <i class="fa fa-edit"></i> Save
                        </button>
                        <button type="button" onclick="location.href='{{ url("admin/core/menu/index/") }}' + $('#menu_type_id').val()" class="btn btn-sm btn-danger">
                            <i class='fa fa-remove'></i> Cancel
                        </button>
                    </div>
                    <div class="box-body">
                        {{ form.render('id') }}
                        {{ form.render('csrf') }}
                        <div class="form-group" id="wrapper-title" title="Title">
                            {{ form.render('title') }}
                        </div>

                        {{ form.renderDecorated('path', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('prepend', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('description', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        {{ form.renderDecorated('show_title', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('menu_type_id', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('view_level', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>