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
                        initMCE();
                    }
                }
            })
        });

        // Initialize tinyMce
        function initMCE() {
            console.log("run");
            var editor_config = {
                path_absolute: '{{ url("admin/core/file-manager/basic") }}',
                selector: "#wrapper-admin-widget textarea",
                skin: "light",
                theme: "modern",
                menubar: false,
                plugins: [
                    "advlist autolink lists link image preview hr anchor pagebreak",
                    "wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "template paste textcolor colorpicker textpattern autoresize spellchecker"
                ],
                toolbar: "styleselect fontselect fontsizeselect | forecolor backcolor | bold italic | " +
                "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | " +
                "link image media preview | code",
                image_advtab: true,
                resize: "both",
                width: "100%",
                autoresize_min_height: 400,
                autoresize_max_height: 800,
                theme_advanced_resizing: true,
                image_class_list: [
                    {title: "None", value: ""},
                    {title: "Image Responsive", value: "img-responsive"}
                ],
                file_browser_callback: function (field_name, url, type, win) {
                    var w = window,
                        d = document,
                        e = d.documentElement,
                        g = d.getElementsByTagName("body")[0],
                        x = w.innerWidth || e.clientWidth || g.clientWidth,
                        y = w.innerHeight || e.clientHeight || g.clientHeight;
                    // Url absolute
                    var cmsURL = editor_config.path_absolute + "?field_name=" + field_name + "&lang=" + tinymce.settings.language;
                    if (type === "image") {
                        cmsURL = cmsURL + "&type=images";
                    }
                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: "Filemanager",
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            };

            tinymce.init(editor_config);
        }

        initMCE();
    });
</script>