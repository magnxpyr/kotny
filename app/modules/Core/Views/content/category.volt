{% for item in model.items %}
<div class="container">
    <div class="row">
        {#{{ item.cat.title }}{{ item.u.name }} item.c.created_at#}
        <h1>{{ item.c.title }}</h1>
        <div>{{ helper.htmlDecode(item.c.introtext) }}</div>
    </div>
</div>
{% endfor %}

{{ link_to("/" ~ category, "First") }}
{{ link_to("/" ~ category ~ "/" ~ model.before, "Previous") }}
{{ link_to("/" ~ category ~ "/" ~ model.next, "Next") }}
{{ link_to("/" ~ category ~ "/" ~ model.last, "Last") }}

{#{{ widget.render('Content') }}#}