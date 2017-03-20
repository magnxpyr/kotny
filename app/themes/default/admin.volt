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
    {{ assets.outputCss("header-css") }}
    {{ assets.outputCss("header-css-min") }}
    {{ assets.outputInlineCss() }}
</head>
<body class="skin-blue sidebar-mini fixed {{ sidebar_collapse }}">
{{ content() }}
{{ assets.outputJs("footer-js-min") }}
{{ assets.outputJs("footer-js") }}
{{ assets.outputInlineJs() }}
</body>
</html>