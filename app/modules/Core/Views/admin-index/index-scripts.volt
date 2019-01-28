<script>
    $(document).ready(function() {
        $('#content').DataTable( {
            "processing": true,
            "serverSide": true,
            "searching": false,
            "dom": "frt",
            ajax: {
                url: '{{ url('admin/core/content/search') }}',
                method: "POST",
                deferRender: true,
                headers: { "X-CSRF-Token": ' {{ tokenManager.getToken()}}' }
            },
            "columns": [
                { "data": "hits" },
                { "data": "title" },
                { "data": "created_at" }
            ],
            "order": [[ 2, "desc" ]],
            'columnDefs': [{
                "targets": 2,
                "render": $.fn.dataTable.render.moment("X", "DD-MM-YYYY")
            }, {
                targets: 0,
                render: function (data) {
                    return "<span class='badge bg-gray'>" + data + "</span>";
                }
            }]
        });
    } );
</script>