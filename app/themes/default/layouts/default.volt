<div class="topbar navbar-primary navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">MagnXpyr</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            {{ section.render("menu") }}
        </div>

    </div>
</div>

<div class="main">
    <div id="flash-area">
        {{ flashSession.output() }}
    </div>

    {{ content() }}
</div>

<footer class="main-footer hidden">
    <div class="container">
        <div class="left-box">Powered by <a href="">Magnxpyr Network</a> &copy 2016</div>
        <div class="right-box social-logo">
            <a href=""><i class="facebook icons"></i></a>
            <a href=""><i class="twitter icons"></i></a>
            <a href=""><i class="google-plus icons"></i></a>
            <a href=""><i class="linkedin icons"></i></a>
        </div>
    </div>
</footer>