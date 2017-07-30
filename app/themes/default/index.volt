<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="generator" content="Magnxpyr CMS">
    {% if metaShowAuthor %}
        <meta name="author" content={{ metaAuthor }}>
    {% endif %}
    <meta name="description" content={{ metaDescription }}>
    <meta name="keywords" content={{ metaKeywords }}>
    <meta name="robots" content={{ metaRobots }}>
    <meta name="rights" content={{ metaContentRights }}>
    <meta name="_token" content="{{ token }}">
    {{ get_title() }}

    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css") }}
    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css") }}
    {{ stylesheet_link("assets/default/css/AdminLTE.min.css") }}
    {{ stylesheet_link("assets/default/css/skins/skin-purple.min.css") }}
    {{ stylesheet_link("assets/default/css/pdw.css") }}
    {{ stylesheet_link("assets/default/css/style.css") }}

    {{ assets.outputCss("header-css") }}
    {{ assets.outputCss("header-css-min") }}
    {{ assets.outputInlineCss() }}
</head>
<body class="skin-purple">
{{ content() }}

{{ javascript_include("https://code.jquery.com/jquery-3.2.1.min.js") }}
{{ javascript_include("assets/common/js/mg.js") }}
{{ javascript_include("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js") }}
{{ javascript_include("assets/default/js/app.js") }}
{{ javascript_include("assets/default/js/pdw.js") }}
{{ javascript_include("assets/default/js/mg.js") }}

{{ assets.outputJs("footer-js-min") }}
{{ assets.outputJs("footer-js") }}
{{ assets.outputInlineJs() }}
{% block scripts %} {% endblock %}
</body>
</html>