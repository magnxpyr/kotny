<div class="container">
    <div class="row">
        {#{{ model.cat.title }}{{ model.u.name }} model.c.created_at#}
        {#<h1>{{ model.c.title }}</h1>#}
        <div>{{ helper.htmlDecode(model.c.fulltext) }}</div>
    </div>
</div>