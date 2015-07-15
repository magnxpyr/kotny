<ul class="nav nav-pills">
    <li>{{ link_to("admin/core/menu/new", '<i class="glyphicon glyphicon-plus"></i>New') }}</li>
    <li><a href="#"><i class="glyphicon glyphicon-ok"></i>Publish</a></li>
    <li><a href="#"><i class="glyphicon glyphicon-remove"></i>Unpublish</a></li>
    <li><a href="#"><i class="glyphicon glyphicon-trash"></i>Trash</a></li>
</ul>
{{ content() }}

{{ form("menu/search", "method":"post", "autocomplete" : "off") }}
    <fieldset>
        <div class="form-group">
            <div class="input-group">
                {{ text_field("menu_type_id", "size" : 30, "type": "numeric", "class": "form-control") }}
            </div>
            <div class="input-group">
                {{ text_field("type", "size" : 30, "class": "form-control") }}
            </div>
            <div class="input-group">
                {{ text_field("title", "size" : 30, "class": "form-control") }}
            </div>
            <div class="input-group">
                {{ text_field("status", "size" : 30, "type": "numeric", "class": "form-control") }}
            </div>
        </div>
    </fieldset>
{{ end_form() }}



    <!-- Table -->
    <table class="table">
        <thead>
        <tr>
            <th>Status</th>
            <th>Title</th>
            <th>Role</th>
            <th>Id</th>
        </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
            {% for menu in page.items %}
                <tr>
                    <td>{{ menu.getStatus() }}</td>
                    <td>{{ menu.getTitle() }}</td>
                    <td>{{ menu.getRoleId() }}</td>
                    <td>{{ menu.getId() }}</td>
                    <td>{{ link_to("menu/edit/"~menu.getId(), "Edit") }}</td>
                    <td>{{ link_to("menu/delete/"~menu.getId(), "Delete") }}</td>
                </tr>
            {% endfor %}
        {% endif %}
        </tbody>
    </table>

<nav>
    <ul class="pagination">
        <li>{{ link_to("menu/search", "First") }}</li>
        <li>{{ link_to("menu/search?page="~page.before, "Previous") }}</li>
        <li>{{ link_to("menu/search?page="~page.next, "Next") }}</li>
        <li>{{ link_to("menu/search?page="~page.last, "Last") }}</li>
    </ul>
</nav>