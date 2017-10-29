{{ content() }}
<div class="main-container">
   {{ section.render("header") }}

    <!--Company Logos-->
    <!--<div class="logos-container">-->
    <!--<div class="partners-wrapper">-->
    <!--<h1 class="title">Partners</h1>-->
    <!--</div>-->

    <!--</div>-->
    <!--Company Logos end-->
</div>

<!--Second Carousel start-->
<div class="projects-wrapper">
    <div class="container">
        <div class="title-wrap">
            <h3 class="title">DISCOVER</h3>
            <h1 class="title">Our Project</h1>
        </div>
        <div class="tab-wrapper">
            <div class="tab-nav">
                <ul>
                    <li><a href="#first-tab" data-toggle="collapse">London</a></li>
                    <li><a href="#second-tab" data-toggle="collapse">Paris</a></li>
                    <li><a href="#third-tab" data-toggle="collapse">UK</a></li>
                </ul>
            </div>
            <div class="tab-content-wrapper">
                <div id="first-tab" class="tab-content collapse">
                    <h2 class="tab-title">London</h2>
                    <p>
                        London, is a major European city and a global center for art, fashion, gastronomy and culture.
                        Its picturesque 19th-century cityscape is crisscrossed by wide boulevards and the River Seine.
                    </p>
                </div>
                <div id="second-tab" class="tab-content collapse">
                    <h2 class="tab-title">Paris</h2>
                    <p>
                        Paris, is a major European city and a global center for art, fashion, gastronomy and culture.
                        Its picturesque 19th-century cityscape is crisscrossed by wide boulevards and the River Seine.
                    </p>
                </div>
                <div id="third-tab" class="tab-content collapse">
                    <h2 class="tab-title">UK</h2>
                    <p>
                        UK, is a major European city and a global center for art, fashion, gastronomy and culture.
                        Its picturesque 19th-century cityscape is crisscrossed by wide boulevards and the River Seine.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Second Carousel end-->
{#<div class="skills-container main-container container">#}
    {#<!--Skills -->#}
    {#<div class="skills-inner-container">#}
        {#<div class="skills-wrapper">#}
            {#<div class="logo-wrapper">#}
                {#<div class="java-logo skill-logo">#}
                    {#<span class="java-bg logo-box colored"></span>#}
                    {#<h5 class="title">JAVA</h5>#}
                {#</div>#}

                {#<div class="js-logo skill-logo">#}
                    {#<span class="js-bg logo-box colored"></span>#}
                    {#<h5 class="title">JAVASCRIPT</h5>#}
                {#</div>#}

                {#<div class="php-logo skill-logo">#}
                    {#<span class="php-bg logo-box colored"></span>#}
                    {#<h5 class="title">PHP</h5>#}
                {#</div>#}

                {#<div class="c-logo skill-logo">#}
                    {#<span class="c-bg logo-box colored"></span>#}
                    {#<h5 class="title">C++</h5>#}
                {#</div>#}
            {#</div>#}
        {#</div>#}
    {#</div>#}
    {#<!--Skills end-->#}
{#</div>#}
{{ section.render('footer') }}
{% do addViewJs('index/index-scripts') %}

