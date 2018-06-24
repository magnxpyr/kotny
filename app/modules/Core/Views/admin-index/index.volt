<section class="content">
    {#<div class="row">#}
        {#<div class="col-md-12">#}
            {#<div class="box">#}
                {#<div class="box-header with-border">#}
                    {#<h3 class="box-title">Welcome to Kotny!</h3>#}

                    {#<div class="box-tools pull-right">#}
                        {#<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>#}
                        {#</button>#}
                        {#<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>#}
                    {#</div>#}
                {#</div>#}
                {#<div class="box-body">#}
                    {#<div class="row">#}
                        {#<div class="col-md-4">#}

                        {#</div>#}
                        {#<div class="col-md-4">#}

                        {#</div>#}
                        {#<div class="col-md-4">#}

                        {#</div>#}
                    {#</div>#}
                {#</div>#}
            {#</div>#}
        {#</div>#}
    {#</div>#}
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Articles</h3>

                    <div class="box-tools pull-right">
                        <button onclick="location.href='{{ url("admin/core/content/create") }}'" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="content" class="table table-striped no-margin">
                            <thead>
                            <tr>
                                <th>Hits</th>
                                <th>Title</th>
                                <th>Creation date</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Site information</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="content" class="table table-striped no-margin">
                            <tbody>
                            <tr><td><i class="fa fa-desktop"></i> {{ os }}</td></tr>
                            <tr><td><i class="fa fa-cogs"></i> {{ php }}</td></tr>
                            <tr><td><i class="fa fa-clock-o"></i> Server time: {{ date('d-m-Y H:i:s') }}</td></tr>
                            <tr><td><i class="fa fa-database"></i> {{ config.dbAdaptor }}</td></tr>
                            {#<tr><td><i class="fa fa-power-off"></i> Offline: {{ config.offline ? 'Yes' : 'No' }}</td></tr>#}
                            <tr><td><i class="fa fa-connectdevelop"></i> Development: {{ config.dev ? 'Enabled' : 'Disabled' }}</td></tr>
                            <tr><td><i class="fa fa-connectdevelop"></i> Config environment: {{ config.environment }}</td></tr>
                            <tr><td><i class="fa fa-connectdevelop"></i> Acl adapter: {{ config.aclAdapter }}</td></tr>
                            <tr><td><i class="fa fa-connectdevelop"></i> Cache adapter: {{ config.cacheAdapter }}</td></tr>
                            <tr><td><i class="fa fa-user"></i> Users: {{ countUsers }}</td></tr>
                            <tr><td><i class="fa fa-file-o"></i> Articles: {{ countArticles }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



{% do addViewJs('admin-index/index-scripts') %}
{% do assets.collection('header-css').addJs("https://cdn.datatables.net/v/bs/dt-1.10.16/r-2.2.1/rr-1.2.3/sl-1.2.4/datatables.min.css") %}
{% do assets.collection('footer-js').addJs("https://cdn.datatables.net/v/bs/dt-1.10.16/r-2.2.1/rr-1.2.3/sl-1.2.4/datatables.min.js") %}
{% do assets.collection('footer-js').addJs("https://cdn.datatables.net/plug-ins/1.10.15/dataRender/datetime.js") %}
