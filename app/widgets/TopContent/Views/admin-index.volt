<div class="form-group">
    <label for='widgetCategory' class='control-label col-sm-2'>Categories</label>
    <div class="input-group" id="wrapper-widgetCategories">
        {{ form.render('widgetCategory', ['class': 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for='widgetLimit' class='control-label col-sm-2'>Limit</label>
    <div class="input-group" id="wrapper-widgetLimit">
        {{ form.render('widgetLimit', ['class': 'form-control']) }}
    </div>
</div>