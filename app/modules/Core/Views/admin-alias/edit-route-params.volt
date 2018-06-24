{% if params != null %}
    {% for index, param in params %}
        <div class="input-group input-wrapper" id="wrapper-params{{ index }}">
            <input type="text" id="_paramKey{{ index }}" name="_paramKey[]" value="{{ index }}" class="form-control img-path-input" readonly />
            <span class="input-group-addon" data-input="_paramKey{{ index }}">:</span>
            <input type="text" id="_paramValue{{ index }}" name="_paramValue[]" value="{{ param }}" class="form-control img-path-input"/>
        </div>
    {% endfor %}
{% endif %}