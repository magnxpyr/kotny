<script>
    $(function () {
        $('#wrapper-publish_up').datetimepicker({
            defaultDate: new Date()
        });
        $('#wrapper-publish_down').datetimepicker();

        $('#position').change(function () {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/core/widget/get-order') }}",
                data: {
                    id: $('#id').val(),
                    position: $(this).find(':selected').text()
                },
                success: function (data) {
                    var listItems = '';
                    $.each(data.ordering, function(key, value) {
                        listItems += '<option value=' + key + '>' + value + '</option>';
                    });

                    $('#ordering').html(listItems);
                }
            })
        })

        $('#package_id').change(function () {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/core/widget/render-widget') }}",
                data: {
                    widgetId: $('#id').val(),
                    widgetName: $(this).find(':selected').text()
                },
                success: function (data) {
                    $('#wrapper-admin-widget').html(data.html);
                }
            })
        })
    });
</script>