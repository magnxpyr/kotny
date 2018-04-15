{{ content() }}
<div class="create">
    <form method='post' action="{{ url("admin/core/content/save") }}" id="contentForm">
        <div class="box box-default">
            <div class="box-header with-border create__btnWrapper">
                <button type="submit" form="contentForm" class="btn btn-sm btn-success">
                    <i class="fa fa-save"></i> Save
                </button>
                <button type="button" onclick="location.href='{{ url.previousUri() }}'"
                        class="btn btn-sm btn-danger"><i class='fa fa-remove'></i> Cancel
                </button>
            </div>
            <div class="box-body">
                {{ form.render('id') }}
                {{ form.render('csrf') }}
                <div class="form-group" id="wrapper-title">
                    {{ form.render('title') }}
                </div>

                <div class="form-group" id="wrapper-alias" title="Alias or Slug is generated from the title">
                    {{ form.render('alias') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Intro Text</h3>
                    </div>
                    <div class="box-body preview">
                        <div class="row">
                            <div class="form-group col-md-3 preview__imgWrapper">
                                {{ form.label('introImage') }}
                                <div class="input-group" id="wrapper-introImage">
                                    {{ form.render('introImage') }}
                                    <span class="input-group-btn">
                                        <button class="btn btn-default file-manager" data-input="introImage" type="button">
                                            <i class="fa fa-folder-open"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="input-group preview__imgWrapper__preview" id="wrapper-introImage-preview">
                                    <div class="image" style="background-image: url('{{ form.getEntityValue('introImage') }}')"></div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                {{ form.renderDecorated('introtext', ['label': ['class': 'control-label col-sm-2'], 'group': ['class': 'mce']]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Full Text</h3>
                    </div>
                    <div class="box-body preview">
                        <div class="row">
                            <div class="form-group col-md-3 preview__imgWrapper">
                                {{ form.label('fulltextImage') }}
                                <div class="input-group" id="wrapper-fulltextImage">
                                    {{ form.render('fulltextImage') }}
                                    <span class="input-group-btn">
                                        <button class="btn btn-default file-manager" data-input="fulltextImage" type="button">
                                            <i class="fa fa-folder-open"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="input-group preview__imgWrapper__preview" id="wrapper-fulltextImage-preview">
                                    <div class="image" style="background-image: url('{{ form.getEntityValue('fulltextImage') }}')"></div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                {{ form.renderDecorated('fulltext', ['label': ['class': 'control-label col-sm-2 mce'], 'group': ['class': 'mce']]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {{ form.render('featured') }}
                                    Featured
                                </label>
                            </div>
                        </div>
                        {{ form.renderDecorated('category', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('view_level', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('publish_up', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('publish_down', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('attributeLayout', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaTitle', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaKeywords', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaDescription', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/tinymce.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.4/jquery.tinymce.min.js") %}

{% do addViewJs('admin-content/edit-scripts') %}