

$(document).ready(function(){
    carouselConfig();
    checkHTMLBodyHeight();
    initializeMenu();
    initializeArticlesGrid();
});


function checkHTMLBodyHeight() {
    var HTMLBody = $('body').height();
    var screen = $(window).height();

    if(HTMLBody < screen) {
        $('.main-footer').addClass('stickyFooter');
    }
}

function carouselConfig() {
    $('.owl-carousel').owlCarousel({
        navigation : true,
        dots: true,
        pagination: true,
        items: 1
    });
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

function initializeArticlesGrid() {
    var articlesList = $('.articles-list-wrapper');
    var setting = {
        gap: 5,
        gridWidth: [0,400,600,1200],
        refresh: 500,
        scrollbottom : {
            ele: articlesList,
            endtxt : 'No More~~',
            gap: 300
        }
    };

    articlesList.waterfall(setting);
}