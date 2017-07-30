<div class="topbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">magnxpyr</a>
        </div>
        <div class="navbar-collapse collapse out" id="navbar-collapse">
            {{ widget.render('Menu', ['id': 1]) }}
        </div>
    </div>
</div>

<div class="container">
    {#{% if title %}#}
    {#<section class="content-header">#}
        {#{{ title }}#}
    {#</section>#}
    {#{% endif %}#}

    <div id="flash-area">
        {{ flashSession.output() }}
    </div>
    {{ content() }}
    <hr>
    <footer>
        <p>Powered by <a href="http://www.magnxpyr.com">Magnxpyr Network</a> &copy; {{ date('Y') }}</p>
    </footer>
</div>