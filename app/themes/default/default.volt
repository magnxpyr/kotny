<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{ get_title() }}
    {{ assets.outputCss('header-css') }}
    {{ stylesheet_link('assets/default/css/style.css') }}
    {{ assets.outputJs('header-js') }}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CMS">
    <meta name="author" content="Magnxpyr Network">
</head>
<body>
{{ content() }}
</body>
</html>