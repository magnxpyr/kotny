

$(document).ready(function(){
    checkHTMLBodyHeight();
    initializeMenu();
});


function checkHTMLBodyHeight() {
    var HTMLBody = $('body').height();
    var screen = $(window).height();

    if(HTMLBody < screen) {
        $('.main-footer').addClass('stickyFooter');
    }
}

function initializeMenu() {
    $('.tab-nav ul li:first').addClass('active');
    $('.tab-content:not(:first)').hide();
    $('.tab-nav ul li a').click(function (event) {
        event.preventDefault();
        var content = $(this).attr('href');
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        $(content).show();
        $(content).siblings('.tab-content').hide();
    });
}
