{{ content() }}
{% set level = 0 %}
{% for key, element in menuElements %}
    {% if element.level == level %}
        </li>
    {% elseif element.level > level %}
        <ul>
    {% else %}
        </li>
        {% for i in level - element.level..i %}
            </ul>
            </li>
        {% endfor %}
    {% endif %}

    <li>
    {{ element.title }}
    {% set level = element.title %}

    {% for i in level..i %}
        </li>
        </ul>
    {% endfor %}
{% endfor %}