<div class="form-group">
    <label for='_category' class='control-label col-sm-2'>Categories</label>
    <div class="input-group" id="wrapper-widgetCategories">
        {{ form.render('_category', ['class': 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for='_limit' class='control-label col-sm-2'>Limit</label>
    <div class="input-group" id="wrapper-widgetLimit">
        {{ form.render('_limit', ['class': 'form-control']) }}
    </div>
</div>