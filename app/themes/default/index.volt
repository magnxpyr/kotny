<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{ get_title() }}
    {{ assets.outputCss("header-css") }}
    {{ assets.outputCss("header-css-min") }}

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="CMS">
    <meta name="author" content="Magnxpyr Network">
</head>
<body class="skin-purple">
{{ content() }}
{{ assets.outputJs("footer-js-min") }}
{{ assets.outputJs("footer-js") }}
</body>
</html>