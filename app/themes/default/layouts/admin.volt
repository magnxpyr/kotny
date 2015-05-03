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
        {{ partial("menu") }}
    </div>
</nav>

<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>&copy; Company 2015</p>
    </footer>
</div>