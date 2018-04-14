{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <button onclick="location.href='{{ url("admin/core/menu/create/") }}'+ $('#menuType').val()" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New</button>

                <button data-url="{{ url("admin/core/menu/saveTree") }}" data-root="menu" class="btn btn-sm btn-success sortableSave">
                    <i class="fa fa-save"></i> Save</button>
            </div>
            <div class="col-sm-offset-3 col-md-offset-3  col-sm-3 col-md-3">

                <div class="form-group">
                    {{ select("menuType", menuType, "using": ["id", "title"], "class": "form-control",
                        "onchange": "javascript: window.location = '" ~ url("admin/core/menu/index/") ~
                        "' + this.options[this.selectedIndex].value;") }}
                </div>

            </div>
        </div>
    </div>

    {#<div class="box-body">#}
        {#{{ widget.render('GridView',#}
            {#[#}
                {#'columns': [#}
                    {#['data': 'title'],#}
                    {#['data': 'status'],#}
                    {#['data': 'view_level'],#}
                    {#['data': 'id', 'searchable': false]#}
                {#],#}
                {#'url': url('admin/core/menu/search'),#}
                {#'actions': [#}
                    {#'update': url('admin/core/menu/edit'),#}
                    {#'delete': url('admin/core/menu/delete')#}
                {#],#}
                {#'tableId': 'table',#}
                {#'order': [],#}
                {#'options': [#}
                    {#'ordering': false,#}
                    {#'rowReorder': [#}
                        {#'update': false,#}
                        {#'selector': 'td:nth-child(2)'#}
                    {#]#}
                {#]#}
            {#],#}
            {#['cache': false]#}
        {#) }}#}
    {#</div>#}

    <div class="box-body">
        {{ widget.render("SortableView",
            [
                'model': model,
                'actions': [
                    'update': url('admin/core/menu/edit'),
                    'delete': url('admin/core/menu/delete')
                ],
                'header': ['Title', 'Status', 'View Level', 'Id'],
                'data': [
                    f('$model->getTitle()'),
                    f('$this->helper->getArticleStatus($model->getStatus())'),
                    f('$model->viewLevel->getName()'),
                    f('$model->getId()')
                ],
                'colSize': [5, 2, 2, 2, 1]
            ]
        ) }}
    </div>
</div>

{#{% do assets.collection('footer-js').addJs("assets/mg_admin/js/datatables.treeGrid.js") %}#}