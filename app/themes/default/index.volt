<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="generator" content="Magnxpyr CMS">
    {% if metaShowAuthor %}
        <meta name="author" content="{{ metaAuthor }}">
    {% endif %}
    <meta name="description" content="{{ metaDescription }}">
    <meta name="keywords" content="{{ metaKeywords }}">
    <meta name="robots" content="{{ metaRobots }}">
    <meta name="rights" content="{{ metaContentRights }}">
    <meta name="_token" content="{{ token }}">
    {{ get_title() }}

    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css") }}
    {{ stylesheet_link("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css") }}
    {{ assets.outputCss("header-css") }}
    {{ stylesheet_link("assets/default/css/main.min.css") }}
    {{ assets.outputViewCss() }}
    {{ assets.outputInlineCss() }}

    {% if config.dev %}
        {{ stylesheet_link("assets/default/css/pdw.css") }}
    {% endif %}
</head>
<body class="skin-purple">
{{ content() }}

{{ javascript_include("https://code.jquery.com/jquery-3.2.1.min.js") }}
{{ javascript_include("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js") }}
{{ assets.outputJs("footer-js") }}
{{ javascript_include("assets/default/js/main.min.js") }}
{{ assets.outputViewJs() }}
{{ assets.outputInlineJs() }}

{% if config.dev %}
    {{ javascript_include("assets/default/js/pdw.js") }}
{% endif %}
{% if cookies.get('cookie-accepted') is not null %}
    <script type="text/javascript" id="cookieinfo"
            src="{{ url('/assets/default/js/cookieinfo.min.js') }}"
            data-bg="rgb(238, 238, 238)"
            data-fg="rgb(51, 51, 51)"
            data-link="rgb(49, 168, 240)"
            data-cookie="cookie-accepted"
            data-text-align="left"
            data-moreinfo="{{ url('/cookie-policy') }}"
            data-close-text="Got it!">
    </script>
{% endif %}
</body>
</html>