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
    {{ stylesheet_link("https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css") }}
    {{ stylesheet_link("https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css") }}
    {{ stylesheet_link("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css") }}
    {{ stylesheet_link("assets/mg_admin/css/AdminLTE.min.css") }}
    {{ stylesheet_link("assets/mg_admin/css/skins/skin-blue.min.css") }}
    {{ stylesheet_link("assets/default/css/pdw.css") }}
    {{ stylesheet_link("assets/mg_admin/css/style.css") }}

    {{ assets.outputCss("header-css") }}
    {{ assets.outputCss("header-css-min") }}
    {{ assets.outputInlineCss() }}
    {{ assets.outputViewCss() }}
</head>
<body class="skin-blue sidebar-mini fixed {{ sidebar_collapse }}">
{{ content() }}

{{ javascript_include("https://code.jquery.com/jquery-3.2.1.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js") }}
{{ javascript_include("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/nestedSortable/2.0.0/jquery.mjs.nestedSortable.min.js") }}
{{ javascript_include("https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js") }}
{{ javascript_include("https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js") }}
{{ javascript_include("https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js") }}
{{ javascript_include("https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js") }}
{{ javascript_include("https://cdn.datatables.net/plug-ins/1.10.15/dataRender/datetime.js") }}
{{ javascript_include("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js") }}
{{ javascript_include("assets/common/js/mg.js") }}
{{ javascript_include("assets/mg_admin/js/app.js") }}
{{ javascript_include("assets/default/js/pdw.js") }}
{{ javascript_include("assets/mg_admin/js/mg.js") }}

{{ assets.outputJs("footer-js-min") }}
{{ assets.outputJs("footer-js") }}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
{{ javascriptInclude("assets/mg_admin/js/bootstrap.modal.js") }}
{{ assets.outputInlineJs() }}
{{ assets.outputViewJs() }}
</body>
</html>