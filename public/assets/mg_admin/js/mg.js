/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
        $("#wrapper-"+elemId+"-preview img").attr("src", val.url);
    });
}

function closeModal() {
    $('.modal-backdrop').remove();
    $('.filemanager-modal').remove();
    $("body").removeClass("modal-open");
}

