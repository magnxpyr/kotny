{{ content() }}
<div class="col-md-6 col-sm-6">
    {{ form("menu/search", "method":"post", "autocomplete" : "off", "class": "form-horizontal") }}
        <fieldset>
            <div class="form-group">
                <label for="menu_type_id" class="control-label col-sm-4">Menu Type</label>
                <div class="input-group">
                    {{ text_field("menu_type_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>

                <label for="type" class="control-label col-sm-4">Type</label>
                <div class="input-group">
                    {{ text_field("type", "size" : 30, "class": "form-control") }}
                </div>

                <label for="title" class="control-label col-sm-4">Title</label>
                <div class="input-group">
                    {{ text_field("title", "size" : 30, "class": "form-control") }}
                </div>

                <label for="status" class="control-label col-sm-4">Status</label>
                <div class="input-group">
                    {{ text_field("status", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>
        </fieldset>
    </form>
</div>


<div class="panel panel-default">
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
</div>
<nav>
    <ul class="pagination">
        <li>{{ link_to("menu/search", "First") }}</li>
        <li>{{ link_to("menu/search?page="~page.before, "Previous") }}</li>
        <li>{{ link_to("menu/search?page="~page.next, "Next") }}</li>
        <li>{{ link_to("menu/search?page="~page.last, "Last") }}</li>
    </ul>
</nav>