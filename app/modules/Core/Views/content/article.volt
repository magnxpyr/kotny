{% set images = model.content.getImagesArray() %}
<div class="container post-container">
    {% if (auth.isEditor()) %}
        <div class="btn-wrapper">
            <a href="{{ url("/admin/core/content/edit/" ~ model.content.id) }}" class="btn btn-primary btn-borderless">Edit</a>
        </div>
    {% endif %}
    <div class="post-wrapper">
        {% if images and images.fulltextImage %}
        <div class="post-featured-images row">
            <img src="{{ images.fulltextImage }}"/>
        </div>
        {% endif %}
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