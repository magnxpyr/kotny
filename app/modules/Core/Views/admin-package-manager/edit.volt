{{ content() }}
<div class="create">
    <form method='post' action="{{ url("admin/core/package-manager/save") }}" id="widgetForm">
        <div class="row">
            <div class="col-md-9">

                <div class="box box-default">
                    <div class="box-header with-border create__btnWrapper">
                        <button type="submit" form="widgetForm" class="btn btn-sm btn-success">
                            <i class="fa fa-edit"></i> Save
                        </button>
                        <button type="button" onclick="location.href='{{ url("admin/core/package-manager/index") }}'"
                                class="btn btn-sm btn-danger">
                            <i class='fa fa-remove'></i> Cancel
                        </button>
                    </div>
                    <div class="box-body">
                        {{ form.render('id') }}
                        {{ form.render('csrf') }}

                        <div class="row">
                            <div class="col-sm-9">
                                {{ form.renderDecorated('name', ['label': ['class': 'control-label']]) }}
                            </div>
                            <div class="col-sm-3">
                                {{ form.renderDecorated('version', ['label': ['class': 'control-label']]) }}
                            </div>
                        </div>

                        {{ form.renderDecorated('author', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('website', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('description', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('type', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>