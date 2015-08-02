<nav class="topbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">magnxpyr</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">
            {{ widget.render('Menu', ['id': 1]) }}
        </div>

    </div>
</nav>

<div class="container">
    <section class="content-header">
        {{ title }}
    </section>
    {{ flashSession.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>Powered by <a href="http://www.magnxpyr.com">Magnxpyr Network</a> &copy; {{ date('Y') }}</p>
    </footer>
</div>