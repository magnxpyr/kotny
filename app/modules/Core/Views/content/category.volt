{% for item in model.items %}
<div class="container">
    <div class="row">
        {#{{ item.category.title }}{{ item.user.name }} item.content.created_at#}
        <h1><a href="{{ url(item.category.alias ~ "/" ~ item.content.id ~ "-" ~ item.content.alias) }}">{{ item.content.title }}</a></h1>
        {% if (acl.isRole(3)) %}
            <a href="{{ url("/admin/core/content/edit/" ~ item.content.id) }}" class="btn btn-primary">Edit</a>
        {% endif %}
        <div class="meta">
            <span>Posted by:</span>
            <span class="author">
                {{ item.user.name }}
                on
            </span>
            <span>{{ date('d M Y', item.content.created_at) }}</span>
        </div>
        <div>{{ helper.htmlDecode(item.content.introtext) }}</div>
    </div>
</div>
{% endfor %}

{{ link_to("/" ~ category, "First") }}
{{ link_to("/" ~ category ~ "/" ~ model.before, "Previous") }}
{{ link_to("/" ~ category ~ "/" ~ model.next, "Next") }}
{{ link_to("/" ~ category ~ "/" ~ model.last, "Last") }}

{#{{ widget.render('Content') }}#}