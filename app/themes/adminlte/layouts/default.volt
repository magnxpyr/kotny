<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo $this->url->get('admin'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>MG</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>MG</b> Admin</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top"  role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                {{ widget.render('Menu', ['_menu': 2]) }}
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                {{ navigation['html'] }}
            </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{ title }}
            <ol class="breadcrumb">
                {{ navigation['breadcrumb'] }}
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div id="flash-area">
                {{ flashSession.output() }}
            </div>
            {{ content() }}
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Version {{ constant('MG_VERSION') }}
        </div>
        <!-- Default to the left -->
        Powered by {{ link_to('http://www.magnxpyr.com', 'Magnxpyr Network', false) }} &copy; {{ date('Y') }}
    </footer>
</div><!-- ./wrapper -->