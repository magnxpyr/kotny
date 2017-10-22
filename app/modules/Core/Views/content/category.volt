<div class="main-wrapper">
    {#<div class="loading-spinner">#}
        {#<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>#}
    {#</div>#}
    <div class="articles-list-wrapper">
        {% for item in model.items %}
        <div class="container hidden">
            <div class="inner">
                {% if (auth.isEditor()) %}
                    <div class="btn-wrapper">
                        <a href="{{ url("/admin/core/content/edit/" ~ item.content.id) }}" class="btn btn-primary btn-borderless">Edit</a>
                    </div>
                {% endif %}
                <div class="article-info">
                    <div class="post-featured-images row">
                        <img src="{{ item.content.getImages() }}"/>
                    </div>
                    <a href="{{ url(item.category.alias ~ "/" ~ item.content.id ~ "-" ~ item.content.alias) }}">
                        <h3 class="title">
                            {{ item.content.title }}
                        </h3>
                    </a>
                    <div class="meta">
                        <span>by </span>
                        <span class="author">
                            {{ item.user.name }}
                            on
                        </span>
                        <span>{{ date('d M Y', item.content.created_at) }}</span>
                    </div>
                    <div>{{ helper.htmlDecode(item.content.introtext) }}</div>
                </div>
            </div>
        </div>
        {% endfor %}
    </div>
    {% if model.total_pages > 1 %}
        {{ link_to("/" ~ category, "First") }}
        {{ link_to("/" ~ category ~ "/" ~ model.before, "Previous") }}
        {{ link_to("/" ~ category ~ "/" ~ model.next, "Next") }}
        {{ link_to("/" ~ category ~ "/" ~ model.last, "Last") }}
    {% endif %}
</div>

{% do assets.collection('footer-js').addJs("//linzap.github.io/waterfall/waterfall-light.js") %}
{% do addViewJs('content/category-scripts') %}
