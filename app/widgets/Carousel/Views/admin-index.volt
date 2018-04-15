<div class="form-group">
    <label for="_images" class="control-label">Images</label>
    <div id="wrapper-widget-images">
    {% if params['_images'] is empty %}
        <div class="input-group input-wrapper" id="wrapper-widget-images0">
            <div class="input-group">
                <span class="input-group-addon delete" onclick="deleteImageInput('wrapper-widget-images0')">
                    <i class="fa fa-minus"></i>
                </span>
                <input type="text" id="_images0" name="_images[]" value="" class="form-control img-path-input"/>
                <span class="input-group-addon file-manager" data-input="_images0">
                    <i class="fa fa-folder-open"></i>
                </span>
            </div>
            <textarea id="_text0" name="_text[]" class="form-control"></textarea>
        </div>
    {% else %}
        {% for index, image in params['_images'] %}
            <div class="input-group input-wrapper" id="wrapper-widget-images{{ index }}">
                <div class="input-group">
                    <span class="input-group-addon delete" onclick="deleteImageInput('wrapper-widget-images{{ index }}')">
                        <i class="fa fa-minus"></i>
                    </span>
                    <input type="text" id="_images{{ index }}" name="_images[]" value="{{ image }}" class="form-control img-path-input"/>
                    <span class="input-group-addon file-manager" data-input="_images{{ index }}">
                        <i class="fa fa-folder-open"></i>
                    </span>
                </div>
                <textarea id="_text{{ index }}" name="_text[]" class="form-control">{{ params['_text'][index] }}</textarea>

            </div>
        {% endfor %}
    {% endif %}
    </div>
    <div class="input-group">
        <button class="btn btn-primary" onclick="addImageInput()"><i class="fa fa-plus"></i> Add image</button>
    </div>
</div>