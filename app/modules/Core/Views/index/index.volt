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

<!--Newsfeed start-->
<div class="newsfeed-wrapper">
    <h1 class="title">Latest Posts</h1>
    <div class="news-container container">
        <div class="news-wrapper">
            <div class="news-box">
                <div class="overlay"></div>
                <a class="wrap" href="">
                    <div class="news-image">
                        <img src="http://www.mybligr.com/wp-content/uploads/2017/01/most-beautiful-tiger-animals-pics-images-photos-pictures-6.jpg">
                    </div>
                    <div class="text-wrapper">
                        <h3>Lorem Ipsum</h3>

                        <div class="news-content">
                            Bacon ipsum dolor amet brisket t-bone hamburger,
                            strip steak corned beef meatball pancetta porchetta tongue chicken sausage swine sirloin
                            tail.
                            Pig cow doner filet mignon. Chuck spare ribs rump, flank meatball kevin kielbasa short loin
                            tail.
                        </div>
                    </div>
                </a>
            </div>
            <div class="news-box">
                <div class="overlay"></div>
                <a href="">
                    <div class="news-image">
                        <img src="https://s-media-cache-ak0.pinimg.com/originals/95/5d/42/955d4284905d149ea4967ff586e89b41.jpg">
                    </div>
                    <div class="text-wrapper">
                        <h3>Lorem Ipsum</h3>

                        <div class="news-content">
                            Bacon ipsum dolor amet brisket t-bone hamburger,
                            strip steak corned beef meatball pancetta porchetta tongue chicken sausage swine sirloin
                            tail.
                            Pig cow doner filet mignon. Chuck spare ribs rump, flank meatball kevin kielbasa short loin
                            tail.
                        </div>
                    </div>
                </a>
            </div>
            <div class="news-box">
                <div class="overlay"></div>
                <a href="">
                    <div class="news-image">
                        <img src="http://wallpaperpawn.us/wp-content/uploads/2016/07/animal-wallpaper-images-cute-ba-animal-wallpapers-wallpapers-backgrounds-images-art.jpg">
                    </div>
                    <div class="text-wrapper">
                        <h3>Lorem Ipsum</h3>

                        <div class="news-content">
                            Bacon ipsum dolor amet brisket t-bone hamburger,
                            strip steak corned beef meatball pancetta porchetta tongue chicken sausage swine sirloin
                            tail.
                            Pig cow doner filet mignon. Chuck spare ribs rump, flank meatball kevin kielbasa short loin
                            tail.
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <h4 class="title"><a href="{{ url("/articles") }}">SEE ALL ARTICLES</a></h4>
</div>
<!--Newsfeed end-->


{% do assets.collection('footer-js').addJs("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js") %}
{% do addViewJs('index/index-scripts') %}

