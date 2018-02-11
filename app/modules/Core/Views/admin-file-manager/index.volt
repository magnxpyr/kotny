<div id="filemanager" class="filemanager"></div>

{% do assets.collection('header-css').addCss("vendor/filemanager-ui/css/filemanager-ui-without.min.css") %}
{% do assets.collection('footer-js').addJs("vendor/filemanager-ui/js/filemanager-ui-without.min.js") %}
{%
do assets.addInlineJs('
    $(function() {
        $("#filemanager").filemanager({
            url: "'~url("admin/core/file-manager/connector")~'",
            languaje: "US",
            upload_max: 5,
            views: "thumbs",
            insertButton: false,
            headers: { "X-CSRF-TOKEN": "'~token~'" }
        });
    });
') %}