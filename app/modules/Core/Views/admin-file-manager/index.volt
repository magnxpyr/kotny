<div id="elfinder"></div>

{%
do assets.addInlineJs('
    $().ready(function() {
        $("#elfinder").elfinder({
        lang: "en",             // language (OPTIONAL)
        url : "'~url("vendor/elFinder/php/connector.minimal.php")~'" // connector URL (REQUIRED)
        }).elfinder("instance");
    });
') %}