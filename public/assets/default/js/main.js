$(document).ready(function(){
    ajaxSetup();
    initializeMenu();
    hideAlertMesssage();
    checkHTMLBodyHeight();
});

$(window).on('load resize', function() {
    //make footer visible after checking html body height
    $('.main-footer').removeClass('hidden');
    checkHTMLBodyHeight();
});

function ajaxSetup() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr('content')
        }
    });
}

function checkHTMLBodyHeight() {
    var HTMLBody = $('body').height();
    var screen = $(window).height();

    if(screen > HTMLBody) {
        $('.main-footer').addClass('stickyFooter');
    } else {
        $('.main-footer').removeClass('stickyFooter');
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
    $('body').on('click', function () {
        $(this).find('.alert-dismissible').fadeOut('slow');
    })
}