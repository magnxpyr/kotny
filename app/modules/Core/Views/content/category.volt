<div class="main-wrapper">
    <div class="articles-list-wrapper">
        {% for item in model.items %}
            {% set images = item.content.getImagesArray() %}
            <div class="container hidden">
                <div class="inner">
                    {% if (auth.isEditor()) %}
                        <div class="btn-wrapper">
                            <a href="{{ url("/admin/core/content/edit/" ~ item.content.id) }}" class="btn btn-primary btn-borderless">Edit</a>
                        </div>
                    {% endif %}
                    <div class="article-info">
                        {% if images and images.introImage %}
                        <div class="post-featured-images row">
                            <img src="{{ images.introImage }}"/>
                        </div>
                        {% endif %}
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
    <nav aria-label="...">
        <ul class="pager">
            {% if model.first != model.current %}
                <li class="previous"><a href="{{ url("/" ~ category ~ "/" ~ model.before) }}"><span aria-hidden="true">&larr;</span> Previous</a></li>
            {% endif %}
            {% if model.last != model.current %}
                <li class="next"><a href="{{ url("/" ~ category ~ "/" ~ model.next) }}">Next <span aria-hidden="true">&rarr;</span></a></li>
            {% endif %}
        </ul>
    </nav>
    {% endif %}
</div>

{% do assets.collection('footer-js').addJs("//linzap.github.io/waterfall/waterfall-light.js") %}
{% do addViewJs('content/category-scripts') %}
