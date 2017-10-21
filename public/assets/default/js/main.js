

$(document).ready(function(){
    initializeMenu();
    hideAlertMesssage();
});

$(window).on('load resize', function() {
    //make footer visible after checking html body height
    $('.main-footer').removeClass('hidden');
    checkHTMLBodyHeight();
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

function hideAlertMesssage() {
    $('body').on('click', function() {
        $(this).find('.alert-dismissible').fadeOut('slow');
    })
}
