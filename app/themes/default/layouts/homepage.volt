<div class="topbar navbar-primary navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#menu-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">{{ config.logo }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="menu-navbar-collapse">
            <div class="right">
                {{ section.render("menu") }}
            </div>
        </div>

    </div>
</div>

<div class="main">
    <div id="flash-area">
        {{ flashSession.output() }}
    </div>
    <div class="main-container">
        {{ section.render("homepage-header") }}
    </div>
    {{ section.render('homepage-content-top') }}
    {{ content() }}
    {{ section.render('homepage-content-bottom') }}
</div>


<footer class="main-footer hidden">
    <div class="container">
        <div class="left-box">{{ section.render('footer-left') }}</div>
        <div class="right-box">
            {{ section.render('footer-right') }}
        </div>
        {{ section.render('footer') }}
    </div>
</footer>