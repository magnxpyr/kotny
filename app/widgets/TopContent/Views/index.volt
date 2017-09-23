{% for item in model %}
    <div class="container">
        <div class="row">
            {#{{ item.category.title }}{{ item.user.name }} item.content.created_at#}
            <h1>{{ item.content.title }}</h1>
            <div>{{ helper.htmlDecode(item.content.introtext) }}</div>
        </div>
    </div>
{% endfor %}