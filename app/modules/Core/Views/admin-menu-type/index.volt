{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button onclick="location.href='{{ url("admin/core/menu-type/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
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
                    <th>Id</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined %}
                    {% for menu in page.items %}
                        <tr data-item="item_{{ menu.getId() }}">
                            <td>{{ link_to("admin/core/menu/index/"~menu.getId(), menu.getTitle()) }}</td>
                            <td>{{ menu.getDescription() }}</td>
                            <td>{{ menu.getId() }}</td>
                            <td>
                                {{ link_to("admin/core/menu-type/edit/"~menu.getId(), '<i class="fa fa-edit"></i>') }}
                                <a href="#" class="ajaxDelete" data-url="{{ url("admin/core/menu-type/delete/"~menu.getId()) }}" data-parent-id="#item_{{ menu.getId() }}">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                    {% endfor %}
                {% endif %}
            </tbody>
        </table>
    </div>
</div>