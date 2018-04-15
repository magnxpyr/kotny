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
        });

        $('#package_id').change(function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/core/widget/render-widget') }}",
                data: {
                    widgetId: $('#id').val(),
                    widgetName: $(this).find(':selected').text()
                },
                success: function (data) {
                    $('#wrapper-admin-widget').html(data.html);
                    if (data.html && data.html.search("<textarea") !== -1) {
                        // reinitialize tinymce
                        tinyMCE.remove();
                        initMCE("#wrapper-admin-widget textarea");
                    }
                    $('#wrapper-admin-widget-scripts').html(data.scripts);

                    $('#view').find('option').remove().end().append('<option>None</option>');
                    $.each(data.views, function (i, item) {
                        $('#view').append($('<option>', {
                            value: item,
                            text : item
                        }));
                    });
                    $('#view').find('option:eq(1)').prop('selected', true);

                    $('#layout').find('option').remove().end().append('<option>None</option>');
                    $.each(data.layouts, function (i, item) {
                        $('#layout').append($('<option>', {
                            value: item,
                            text : item
                        }));
                    });
                    if (data.views.length) {
                        $('#layout').find('option:eq(1)').prop('selected', true);
                    }
                }
            })
        });

        initMCE("#wrapper-admin-widget textarea");
    });
</script>

<div id="wrapper-admin-widget-scripts">{{ widgetScripts }}</div>