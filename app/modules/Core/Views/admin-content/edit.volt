{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li>
                    <button type="submit" form="contentForm" class="btn btn-sm btn-block btn-success">
                        <i class="fa fa-edit"></i> Save
                    </button>
                </li>
                <li>
                    <button onclick="location.href='{{ url("admin/core/content/index") }}'"
                            class="btn btn-sm btn-block btn-danger"><i class='fa fa-remove'></i> Cancel
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-body">
        <form method='post' action="{{url("admin/core/content/save")}}" id="contentForm">
            {{ form.render('id') }}
            {{ form.render('csrf') }}
            {{ form.renderDecorated('title', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('alias', ['label': ['class': 'control-label col-sm-2']]) }}

            <div class="form-group">
                {{ form.label('introImage', ['class': 'control-label col-sm-2']) }}
                <div class="input-group col-lg-4" id="wrapper-introImage">
                {{ form.render('introImage') }}
                    <span class="input-group-btn">
                        <button class="btn btn-default file-manager" data-input="introImage" type="button"><i class="fa fa-folder-open"></i></button>
                    </span>
                </div>
                <div class="input-group col-lg-offset-2 col-lg-6" id="wrapper-introImage-preview">
                    <img src="{{ form.getEntityValue('introImage') }}" height="250px" width="250px">
                </div>
            </div>

            {{ form.renderDecorated('introtext', ['label': ['class': 'control-label col-sm-2']]) }}

            <div class="form-group">
                {{ form.label('fulltextImage', ['class': 'control-label col-sm-2']) }}
                <div class="input-group col-lg-4" id="wrapper-fulltextImage">
                    {{ form.render('fulltextImage') }}
                    <span class="input-group-btn">
                        <button class="btn btn-default file-manager" data-input="fulltextImage" type="button"><i class="fa fa-folder-open"></i></button>
                    </span>
                </div>
                <div class="input-group col-lg-offset-2 col-lg-6" id="wrapper-fulltextImage-preview">
                    <img src="{{ form.getEntityValue('fulltextImage') }}" height="250px" width="250px">
                </div>
            </div>

            {{ form.renderDecorated('fulltext', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('category', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('featured', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('status', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('view_level', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('publish_up', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('publish_down', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('metaTitle', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('metaKeywords', ['label': ['class': 'control-label col-sm-2']]) }}
            {{ form.renderDecorated('metaDescription', ['label': ['class': 'control-label col-sm-2']]) }}
        </form>
    </div>
</div>

{% do assets.collection('footer-js').addJs("https://cloud.tinymce.com/stable/tinymce.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.1/jquery.tinymce.min.js") %}
{% do addViewJs('admin-content/edit-scripts') %}