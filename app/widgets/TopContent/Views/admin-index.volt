<div class="form-group">
    <label for='_featured' class='control-label col-sm-2'>{{ t._('Featured') }}</label>
    <div class="input-group" id="wrapper-widgetCategories">
        {{ form.render('_featured', ['class': 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for='_category' class='control-label col-sm-2'>{{ t._('Categries') }}</label>
    <div class="input-group" id="wrapper-widgetCategories">
        {{ form.render('_category', ['class': 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for='_limit' class='control-label col-sm-2'>{{ t._('Limit') }}</label>
    <div class="input-group" id="wrapper-widgetLimit">
        {{ form.render('_limit', ['class': 'form-control']) }}
    </div>
</div>