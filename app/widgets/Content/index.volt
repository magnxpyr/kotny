{% for item in model %}
    <div class="container">
        <div class="row">
            {#{{ item.cat.title }}{{ item.u.name }} item.c.created_at#}
            <h1>{{ item.c.title }}</h1>
            <div>{{ helper.htmlDecode(item.c.introtext) }}</div>
        </div>
    </div>
{% endfor %}