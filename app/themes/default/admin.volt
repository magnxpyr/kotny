<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="url" content="{{ url.getBaseUri() }}">
    <meta name="_token" content="{{ token }}">
    {{ get_title() }}

    {{ stylesheet_link("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css") }}
    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css") }}
    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css") }}
    {{ stylesheet_link("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css") }}
    {{ stylesheet_link("assets/mg_admin/css/AdminLTE.min.css") }}
    {{ stylesheet_link("assets/mg_admin/css/skins/skin-blue.min.css") }}
    {{ assets.outputCss("header-css") }}
    {{ stylesheet_link("assets/default/css/pdw.css") }}
    {{ stylesheet_link("assets/mg_admin/css/style.css") }}
    {{ stylesheet_link("assets/mg_admin/css/main.css") }}
    {{ assets.outputViewCss() }}
    {{ assets.outputInlineCss() }}
</head>
<body class="skin-blue sidebar-mini fixed {{ sidebar_collapse }}">
{{ content() }}

{{ javascript_include("https://code.jquery.com/jquery-3.2.1.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js") }}
{{ javascript_include("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js") }}
{{ assets.outputJs("footer-js") }}
{{ javascript_include("assets/common/js/mg.js") }}
{{ javascript_include("assets/mg_admin/js/app.js") }}
{{ javascript_include("assets/default/js/pdw.js") }}
{{ javascript_include("assets/mg_admin/js/mg.js") }}
{{ javascript_include("assets/mg_admin/js/bootstrap.modal.js") }}
{{ assets.outputViewJs() }}
{{ assets.outputInlineJs() }}
</body>
</html>