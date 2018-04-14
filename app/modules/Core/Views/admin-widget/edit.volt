{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <button type="submit" form="widgetForm" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Save
        </button>
        <button onclick="location.href='{{ url("admin/core/widget/index") }}'" class="btn btn-sm btn-danger">
            <i class='fa fa-remove'></i> Cancel
        </button>
    </div>
    <div class="box-body">
        <form method="post" id="widgetForm" action="{{ url("admin/core/widget/save") }}">
            {{ form.render('csrf') }}
            {{ form.render('id') }}
            {{ form.renderDecorated('title', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('package_id', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('position', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('ordering', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('view_level', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('publish_up', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('publish_down', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('show_title', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('layout', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('view', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('status', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('cache', ['label': ['class': 'control-label col-sm-2']]) }}
            <div id="wrapper-admin-widget">{{ widgetContent }}</div>
        </form>
    </div>
</div>

{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/tinymce.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/jquery.tinymce.min.js") %}
{% do addViewJs('admin-widget/edit-scripts', ['widgetScripts': widgetScripts]) %}