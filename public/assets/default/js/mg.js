/**
 * @copyright   2006 - 2016 Magnxpyr Network
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
});