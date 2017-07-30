<div class="container">
    <div class="row">
        <h1>{{ model.content.title }}</h1>
        {#//TODO#}
        {% if (acl.isRole(3)) %}
            <a href="{{ url("/admin/core/content/edit/" ~ model.content.id) }}" class="btn btn-primary">Edit</a>
        {% endif %}
        <div class="meta">
            <span>Posted by:</span>
            <span class="author">
                {{ model.user.name }}
                on
            </span>
            <span>{{ date('d M Y', model.content.created_at) }}</span>
        </div>
        <div>{{ helper.htmlDecode(model.content.fulltext) }}</div>
    </div>
</div>