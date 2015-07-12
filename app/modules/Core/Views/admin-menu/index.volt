{{ content() }}
<h1>Search menu</h1>

<div class="col-md-6 col-sm-6">
    {{ form("menu/search", "method":"post", "autocomplete" : "off", "class": "form-horizontal") }}
        {{ link_to("menu/new", "Create menu") }}
        <fieldset>
            
            <div class="form-group">
                <label for="id" class="control-label col-sm-4">Id</label>
                <div class="input-group">
                    {{ text_field("id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="menu_type_id" class="control-label col-sm-4">Menu Of Type</label>
                <div class="input-group">
                    {{ text_field("menu_type_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="type" class="control-label col-sm-4">Type</label>
                <div class="input-group">
                    {{ text_field("type", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="title" class="control-label col-sm-4">Title</label>
                <div class="input-group">
                    {{ text_field("title", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="path" class="control-label col-sm-4">Path</label>
                <div class="input-group">
                    {{ text_field("path", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="link" class="control-label col-sm-4">Link</label>
                <div class="input-group">
                    {{ text_field("link", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="control-label col-sm-4">Status</label>
                <div class="input-group">
                    {{ text_field("status", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="parent_id" class="control-label col-sm-4">Parent</label>
                <div class="input-group">
                    {{ text_field("parent_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="level" class="control-label col-sm-4">Level</label>
                <div class="input-group">
                    {{ text_field("level", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="lft" class="control-label col-sm-4">Lft</label>
                <div class="input-group">
                    {{ text_field("lft", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="rgt" class="control-label col-sm-4">Rgt</label>
                <div class="input-group">
                    {{ text_field("rgt", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="role_id" class="control-label col-sm-4">Role</label>
                <div class="input-group">
                    {{ text_field("role_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Search", "class": "btn btn-success col-sm-offset-4") }}
            </div>
        </fieldset>
    </form>
</div>


<div class="panel panel-default">
    <!-- Table -->
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Menu Of Type</th>
            <th>Type</th>
            <th>Title</th>
            <th>Path</th>
            <th>Link</th>
            <th>Status</th>
            <th>Parent</th>
            <th>Level</th>
            <th>Lft</th>
            <th>Rgt</th>
            <th>Role</th>

        </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
            {% for menu in page.items %}
                <tr>
                    <td>{{ menu.getId() }}</td>
                    <td>{{ menu.getMenuTypeId() }}</td>
                    <td>{{ menu.getType() }}</td>
                    <td>{{ menu.getTitle() }}</td>
                    <td>{{ menu.getPath() }}</td>
                    <td>{{ menu.getLink() }}</td>
                    <td>{{ menu.getStatus() }}</td>
                    <td>{{ menu.getParentId() }}</td>
                    <td>{{ menu.getLevel() }}</td>
                    <td>{{ menu.getLft() }}</td>
                    <td>{{ menu.getRgt() }}</td>
                    <td>{{ menu.getRoleId() }}</td>

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