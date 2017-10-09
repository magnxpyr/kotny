
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li>
                    <button type="submit" form="menuForm" class="btn btn-sm btn-block btn-success"><i
                                class="fa fa-edit"></i> Save
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
        {{ form.renderForm(
        url("admin/core/content/save"),
        [
        'form': ['id': 'menuForm'],
        'label': ['class': 'control-label col-sm-2']
        ]
        ) }}
    </div>
</div>

{% do assets.collection('footer-js').addJs("https://cloud.tinymce.com/stable/tinymce.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.1/jquery.tinymce.min.js") %}
{% do addViewJs('admin-content/edit-scripts') %}