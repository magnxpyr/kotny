/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

$(function ($) {
    window.baseURI = $('meta[name=url]').attr("content");
    var AdminLTEOptions = {
        //Enable sidebar expand on hover effect for sidebar mini
        //This option is forced to true if both the fixed layout and sidebar mini
        //are used together
        sidebarExpandOnHover: false,
        //BoxRefresh Plugin
        enableBoxRefresh: true,
        //Bootstrap.js tooltip
        enableBSToppltip: true
    };

    // Set cookie on sidebar-toogle
    $('.sidebar-toggle').on('click', function (e) {
        //Enable sidebar push menu
        if ($(window).width() > 767) {
            if($("body").hasClass('sidebar-collapse')) {
                Cookies.set('mg-sdbrClp', 1);
            } else {
                Cookies.set('mg-sdbrClp', 0);
            }
        }
    });

    $('.hint-block').each(function () {
        var $hint = $(this);
        $hint.parent().find('label').addClass('help').popover({
            html: true,
            trigger: 'hover',
            placement: 'right',
            content: $hint.html()
        });
    });

    if (typeof jQuery().nestedSortable !== 'undefined' && $.isFunction(jQuery().nestedSortable)) {
        $("ol.sortable").nestedSortable({
            handle: "i.fa-reorder",
            items: "li",
            isTree: true,
            forcePlaceholderSize: true,
            placeholder: "placeholder",
            tolerance: "pointer",
            toleranceElement: "> div"
        });

        $(".sortableSave").click(function () {
            var root = $(this).data("root");
            var url = $(this).data("url");
            var data = $("ol.sortable#root_" + root).nestedSortable("toArray", {startDepthCount: 1});
            if (data) {
                $.post(url, {root: root, data: data}, function (response) {
                    handleResponse(response)
                }, "json");
            }
        });
    }

    $(".box-body").on("click", '.ajaxDelete', function(e) {
        e.preventDefault();
        var data;

        var t = this;
        var parent = $(t).data("parent-id");
        var url = $(t).data("url");
        if (typeof table !== 'undefined') {
            data = table.row($(t).closest(parent)).data();
        }

        if (confirm('Do you really want to delete this item?')) {
            $.post(url, data, function(response) {
                if(response.success) {
                    $(t).closest(parent).remove();
                    ajaxSuccess(response);
                } else {
                    ajaxFailure(response);
                }
            });
        }
    });

    $(".box-body").on("click", '.file-manager', function(e) {
        var inputId = $(this).data("input");
        showBSModal({
            title: 'Change image',
            size: 'large',
            modalClass: 'filemanager-modal',
            body: '<iframe src="' + window.baseURI + 'admin/core/file-manager/basic" frameborder="0" data-input="' + inputId + '"></iframe>'

        });
    });

    $.fn.datetimepicker.defaults.format ='DD-MM-YYYY hh:mm';
    $.fn.datetimepicker.defaults.showTodayButton = true;
});


function handleResponse(response) {
    if (response.success) {
        ajaxSuccess(response);
    } else {
        ajaxFailure(response);
    }
}

function ajaxSuccess(response) {
    var message = "Success";
    if (response.message) {
        message = response.message;
    }
    $("#flash-area").html("<div class=\"alert alert-success alert-dismissible\">" + message + "</div>")
}

function ajaxFailure(response) {
    var message = "Failure";
    if (response.message) {
        message = response.message;
    }
    $("#flash-area").html("<div class=\"alert alert-danger alert-dismissible\">" + message + "</div>")
}

function setData(data) {
    var elemId = $(".filemanager-modal iframe").data("input");
    $.each(data, function(index, val) {
        $("#" + elemId).val(val.url);
        $("#wrapper-"+elemId+"-preview .image").css("background-image", 'url(' + val.url + ')');
        $("#wrapper-"+elemId+"-preview .image").css("background-color", '#f2f2f2');
    });
}

function closeModal() {
    $('.modal-backdrop').remove();
    $('.filemanager-modal').remove();
    $("body").removeClass("modal-open");
}

// Initialize tinyMce
function initMCE(selector) {
    if (selector === undefined) {
        selector = "textarea";
    }
    var editor_config = {
        path_absolute: window.baseURI + 'admin/core/file-manager/basic',
        selector: selector,
        skin_url: window.baseURI + 'vendor/tinymce/skins/light',
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
        branding: false,
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

