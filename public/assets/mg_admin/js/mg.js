/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

$(function ($) {
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
            var data = $("ol.sortable#root_" + root).nestedSortable("toArray", {startDepthCount: 0});
            if (data) {
                $.post(url, {root: root, data: data}, function (response) {
                    if (response.success == true) {
                        console.log("save successfully");
                        //   noty({layout:'center',type:'success',text:'Root "'+root+'" saved',timeout:2000});
                    } else {
                        console.log("save failed");
                    }
                }, "json");
            }
        });
    }

    $(".box-body").on("click", '.ajaxDelete', function(e) {
        e.preventDefault();

        var t = this;
        var parent = $(t).data("parent-id");
        var url = $(t).data("url");
        var data = $(t).data("data");
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

