<div id="filemanager" class="filemanager"></div>

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