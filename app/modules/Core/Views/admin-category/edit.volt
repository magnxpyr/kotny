{{ content() }}
<div class="create">
    <form method='post' action="{{ url("admin/core/category/save") }}" id="contentForm">

        <div class="row">
            <div class="col-md-9">

                <div class="box box-default">
                    <div class="box-header with-border create__btnWrapper">
                        <button type="submit" form="menuForm" class="btn btn-sm btn-success">
                            <i class="fa fa-edit"></i> Save
                        </button>
                        <button type="button" onclick="location.href='{{ url("admin/core/category/index") }}'" class="btn btn-sm btn-danger">
                            <i class='fa fa-remove'></i> Cancel
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

                        {{ form.renderDecorated('description', ['label': ['class': 'control-label col-sm-2']]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        {{ form.renderDecorated('view_level', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('status', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaTitle', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaKeywords', ['label': ['class': 'control-label']]) }}
                        {{ form.renderDecorated('metaDescription', ['label': ['class': 'control-label']]) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>