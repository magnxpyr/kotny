{{ content() }}
<div class="create">
    <form method="post" id="widgetForm" action="{{ url("admin/core/widget/save") }}">
        <div class="box box-default">
            <div class="box-header with-border create__btnWrapper">
                <button type="submit" form="widgetForm" class="btn btn-sm btn-success">
                    <i class="fa fa-edit"></i> Save
                </button>
                <button type="reset" onclick="location.href='{{ url("admin/core/widget/index") }}'" class="btn btn-sm btn-danger">
                    <i class='fa fa-remove'></i> Cancel
                </button>
            </div>
            <div class="box-body">
                {{ form.render('id') }}
                {{ form.render('csrf') }}
                <div class="form-group" id="wrapper-title">
                    {{ form.render('title') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="box box-default">
                    <div class="box-body">
                        {{ form.renderDecorated('package_id', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('position', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('ordering', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('layout', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('cache', ['label': ['class': 'control-label']]) }}
                        <div id="wrapper-admin-widget">{{ widgetContent }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        {{ form.renderDecorated('show_title', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('publish_up', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('publish_down', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('view_level', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('view', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/tinymce.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/jquery.tinymce.min.js") %}
{% do addViewJs('admin-widget/edit-scripts', ['widgetScripts': widgetScripts]) %}