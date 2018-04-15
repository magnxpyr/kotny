{{ content() }}
<div class="main-container">
   {{ section.render("header") }}
</div>


{{ section.render('content-top') }}
{{ section.render('content-left') }}
{{ section.render('content-mid') }}
{{ section.render('content-right') }}
{{ section.render('content-bottom') }}
{% do addViewJs('index/index-scripts') %}

