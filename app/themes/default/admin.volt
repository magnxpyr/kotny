<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="url" content="{{ url.getBaseUri() }}">
    <meta name="_token" content="{{ token }}">

    {{ get_title() }}
    {{ assets.outputCss("header-css") }}
    {{ assets.outputCss("header-css-min") }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"/>
    {{ assets.outputInlineCss() }}
    {{ assets.outputViewCss() }}
</head>
<body class="skin-blue sidebar-mini fixed {{ sidebar_collapse }}">
{{ content() }}
{{ assets.outputJs("footer-js-min") }}
{{ assets.outputJs("footer-js") }}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
{{ javascriptInclude("assets/mg_admin/js/bootstrap.modal.js") }}
{{ assets.outputInlineJs() }}
{{ assets.outputViewJs() }}
</body>
</html>