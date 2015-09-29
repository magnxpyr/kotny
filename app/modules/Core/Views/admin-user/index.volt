{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/user/new") }}'" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-plus"></i> New</button>
                    </li>
                </ul>
            </div>
            <div class="col-sm-offset-3 col-md-offset-3  col-sm-3 col-md-3">

            </div>
        </div>
    </div>

    <div class="box-body">
        <table id="table" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th data-column-id="id">#</th>
                <th data-column-id="username">Username</th>
                <th data-column-id="email">Email</th>
                <th data-column-id="role_id">Role</th>
                <th data-column-id="status">Status</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>

    </div>
</div>

{% do assets.addInlineJs('
    $("#table tfoot th").each( function () {
        var title = $("#table thead th").eq( $(this).index() ).text();
        $(this).html("<input type=\"text\" placeholder=\"Search " +title+ "\" />");
    } );

    var table = $("#table").DataTable({
        serverSide: true,
        ajax: {
            url: "'~url("admin/core/user/search")~'",
            method: "POST",
            deferRender: true
        },
        columns: [
            {data: "id", searchable: false},
            {data: "username"},
            {data: "email"},
            {data: "role_id"},
            {data: "status"}
        ],
        columnDefs: [
            {
                render: function ( data, type, row ) {
                    return function (o) { return "<i class=\"fa fa-edit\"></i><i class=\"fa fa-trash\"></i>"; }
                },
                targets:0
            }
        ],
        initComplete: function () {
            var r = $("#table tfoot tr");
            r.find("th").each(function() {
                $(this).css("padding", 8);
            });
            $("#table thead").append(r);
            $("#search_0").css("text-align", "center");
        }
    });
    table.columns().every( function () {
        var that = this;
        $( "input", this.footer() ).on( "keyup change", function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );')
%}