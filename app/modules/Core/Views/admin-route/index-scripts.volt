<script>
    $(document).ready(function() {
        var dt = $('#table').DataTable();
        dt.on('row-reorder', function (e, diff, edit) {
            var result = [];
            var t = $('#table').dataTable();
            for (var i = 0; i < diff.length; i++) {
                var rowDiff = dt.row(diff[i].node).data();
                t.fnUpdate(diff[i].newData, diff[i].node, 1, false, false);
                result.push({id: rowDiff.id, ordering: diff[i].newData});
            }

            $.post("{{ url("/admin/core/route/update-ordering") }}", {
                data: result,
                success: function (response) {
                    ajaxFailure(response);
                }
            });
        });
    });
</script>