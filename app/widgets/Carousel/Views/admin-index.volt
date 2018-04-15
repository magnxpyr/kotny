<div class="form-group">
    <label for="_images" class="control-label col-sm-2">Images</label>
    <div class="input-group">
        <button class="btn btn-primary" onclick="addImageInput()"><i class="fa fa-plus"></i> Add image</button>
    </div>
    <div id="wrapper-widget-images">
    {% if params['_images'] is empty %}
        <div id="wrapper-widget-images0">
            <div class="input-group col-lg-offset-2">
                <input type="text" id="_images0" name="_images[]" value="" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default file-manager" data-input="_images0" type="button">
                        <i class="fa fa-folder-open"></i></button>
                </span>
                <button class="btn btn-xs" onclick="deleteImageInput('wrapper-widget-images0')"><i class="fa fa-minus-square-o"></i></button>
            </div>
            <textarea id="_text0" name="_text[]" class="form-control"></textarea>
        </div>
    {% else %}
        {% for index, image in params['_images'] %}
            <div id="wrapper-widget-images{{ index }}">
                <div class="input-group col-lg-offset-2">
                    <input type="text" id="_images{{ index }}" name="_images[]" value="{{ image }}" class="form-control">
                    <span class="input-group-btn">
                    <button class="btn btn-default file-manager" data-input="_images{{ index }}" type="button">
                        <i class="fa fa-folder-open"></i></button>
                </span>
                    <button class="btn btn-xs" onclick="deleteImageInput('wrapper-widget-images{{ index }}')"><i class="fa fa-minus-square-o"></i></button>
                </div>

                <textarea id="_text{{ index }}" name="_text[]" class="form-control">{{ params['_text'][index] }}</textarea>
            </div>
        {% endfor %}
    {% endif %}
    </div>
</div>