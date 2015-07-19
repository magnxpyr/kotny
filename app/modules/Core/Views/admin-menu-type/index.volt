{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button onclick="location.href='{{ url("admin/core/menu-type/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='glyphicon glyphicon-plus'></i> New</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-warning"><i class="glyphicon glyphicon-trash"></i> Trash</button></li>
            </ul>
        </div>
    </div>

    <div class="box-body">
        <!-- Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Role</th>
                    <th>Id</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined %}
                    {% for menu in page.items %}
                        <tr>
                            <td>{{ menu.getTitle() }}</td>
                            <td>{{ menu.getDescription() }}</td>
                            <td>{{ menu.getRoleId() }}</td>
                            <td>{{ menu.getId() }}</td>
                            <td>{{ link_to("admin/core/menu-type/edit/"~menu.getId(), "Edit") }}</td>
                            <td>{{ link_to("admin/core/menu-type/delete/"~menu.getId(), "Delete") }}</td>
                        </tr>
                    {% endfor %}
                {% endif %}
            </tbody>
        </table>
    </div>
</div>