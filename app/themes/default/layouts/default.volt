<div class="wrapper-in">

    <header>
        {{ partial('header') }}
    </header>

    {{ partial('menu') }}

    <div id="main">

        {{ content() }}

    </div>

    <footer>
        {{ partial('footer') }}
    </footer>

</div>