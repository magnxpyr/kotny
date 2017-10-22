<div class="container post-container">
    {% if (auth.isEditor()) %}
     <ul class="nav nav-tabs post-admin-tab">
        <li class="active pull-right">
            <a href="{{ url("/admin/core/content/edit/" ~ model.content.id) }}">Edit</a>
        </li>
    </ul>
    {% endif %}
    <div class="post-wrapper">
        <h2 class="post-title">{{ model.content.title }}</h2>
        <div class="meta">
            <span class="post-author">
                <span>by </span>
                <span class="author">
                    {{ model.user.name }}
                    on
                </span>
            </span>
            <span class="post-date">{{ date('d M Y', model.content.created_at) }}</span>
        </div>
        <div class="post-content">{{ helper.htmlDecode(model.content.fulltext) }}</div>
    </div>
</div>