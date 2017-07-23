$(function ($) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr('content')
        }
    });
});