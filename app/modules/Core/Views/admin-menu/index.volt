{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button onclick="location.href='{{ url("admin/core/menu/new") }}'" class="btn btn-sm btn-block btn-primary"><i class='fa fa-plus'></i> New</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-success"><i class="fa fa-check"></i> Publish</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-danger"><i class="fa fa-remove"></i> Unpublish</button></li>
                <li><button onclick="location.href=''" class="btn btn-sm btn-block btn-warning"><i class="fa fa-trash"></i> Trash</button></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <div class="dataTables_filter">
                {{ form("menu/search", "method":"post", "autocomplete": "off", "class": "form-inline") }}
                <fieldset>
                    <div class="form-group">
                        <div class="input-group">
                            {{ text_field("menu_type_id", "type": "numeric", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("type", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("title", "class": "form-control input-sm") }}
                        </div>
                        <div class="input-group">
                            {{ text_field("status", "type": "numeric", "class": "form-control input-sm") }}
                        </div>
                    </div>
                </fieldset>
                {{ end_form() }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="dataTables_wrapper form-inline dt-bootstrap">
            <!-- Table -->
            <table class="table table-bordered table-striped">
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

            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                </div>
                <div class="col-sm-7">
                    <nav>
                        <ul class="pagination">
                            <li class="paginate_button previous disabled" id="example2_previous">
                                <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Previous</a></li>
                            <li class="paginate_button active"><a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0">1</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0">2</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0">3</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0">4</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0">5</a></li>
                            <li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0">6</a></li>
                            <li class="paginate_button next" id="example2_next"><a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <nav>
                <ul class="pagination">
                    <li>{# link_to("menu/search", "First") #}</li>
                    <li>{# link_to("menu/search?page="~page.before, "Previous") #}</li>
                    <li>{# link_to("menu/search?page="~page.next, "Next") #}</li>
                    <li>{# link_to("menu/search?page="~page.last, "Last") #}</li>
                </ul>
            </nav>
        </div>
    </div>
</div>