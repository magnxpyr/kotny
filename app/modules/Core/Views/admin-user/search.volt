{{ content() }}
<h1>Search</h1>
<div class="btn-toolbar">
    {{ link_to("access_list/index", "Go Back", "class": "btn btn-success") }}
    {{ link_to("access_list/new", "Create", "class": "btn btn-success") }}
</div>

<div class="panel panel-default">
    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Roles Of Name</th>
                <th>Resources Of Name</th>
                <th>Access Of Name</th>
                <th>Allowed</th>

            </tr>
        </thead>
        <tbody>
            {% if page.items is defined %}
                {% for access_list in page.items %}
                    <tr>
                        <td>{{ access_list.getRole() }}</td>

                        <td>{{ link_to("access_list/edit/"~access_list.getRole(), "Edit") }}</td>
                        <td>{{ link_to("access_list/delete/"~access_list.getRole(), "Delete") }}</td>
                    </tr>
                {% endfor %}
            {% endif %}
        </tbody>
    </table>

</div>

<nav>
    <ul class="pagination">
        <li>{{ link_to("access_list/search", "First") }}</li>
        <li>{{ link_to("access_list/search?page="~page.before, "Previous") }}</li>
        <li>{{ link_to("access_list/search?page="~page.next, "Next") }}</li>
        <li>{{ link_to("access_list/search?page="~page.last, "Last") }}</li>
    </ul>
</nav>
