{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="col-sm-6">
            <ul class="nav nav-pills">
                <li><button type="submit" form="menuForm" class="btn btn-sm btn-block btn-success"><i class="fa fa-edit"></i> Save</button></li>
                <li><button onclick="location.href='{{ url("admin/core/content/index") }}'" class="btn btn-sm btn-block btn-danger"><i class='fa fa-remove'></i> Cancel</button></li>
            </ul>
        </div>
    </div>
    <div class="box-body">
        {{ form.renderForm(
            url("admin/core/content/save"),
            [
                'form': ['id': 'menuForm'],
                'label': ['class': 'control-label col-sm-2']
            ]
        ) }}
    </div>
</div>

{%
do assets.addInlineJs('
    $(function() {
        var editor_config = {
            path_absolute : "'~url("admin/core/file-manager/basic")~'",

            selector: "textarea",
            skin: "light",
            theme: "modern",
            menubar: false,
            plugins: [
             "advlist autolink lists link image preview hr anchor pagebreak",
             "wordcount visualblocks visualchars code fullscreen",
             "insertdatetime media nonbreaking save table contextmenu directionality",
             "template paste textcolor colorpicker textpattern autoresize spellchecker"
            ],
            toolbar: "styleselect fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media preview | code",
            image_advtab: true,
            resize: "both",
            width: "100%",
            autoresize_min_height: 400,
            autoresize_max_height: 800,
            theme_advanced_resizing : true,
            image_class_list: [
             {title: "None", value: ""},
             {title: "Image Responsive", value: "img-responsive"}
            ],
            file_browser_callback : function(field_name, url, type, win) {
                     var w = window,
                     d = document,
                     e = d.documentElement,
                     g = d.getElementsByTagName("body")[0],
                     x = w.innerWidth || e.clientWidth || g.clientWidth,
                     y = w.innerHeight|| e.clientHeight|| g.clientHeight;
                 // Url absolute
                var cmsURL = editor_config.path_absolute+"?field_name="+field_name+"&lang="+tinymce.settings.language;
                if(type == "image") {
                    cmsURL = cmsURL + "&type=images";
                }
                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : "Filemanager",
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };
        tinymce.init(editor_config);
    });
') %}