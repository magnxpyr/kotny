{{ content() }}
{{ form('user/reset-password', 'method':'post') }}
    <fieldset>
        <div class="form-group form-max-size">
            <div class="form-group">
                {{ form.label('password', ['class': 'control-label']) }}
                <div class="input-group">
                    <span class="input-group-addon" id="password-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    {{ form.render('password', ['aria-describedby': 'password-addon']) }}
                </div>
            </div>

            <div class="form-group">
                {{ form.label('repeatPassword', ['class': 'control-label']) }}
                <div class="input-group">
                    <span class="input-group-addon" id="password-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    {{ form.render('repeatPassword', ['aria-describedby': 'password-addon']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ form.render('submit') }}
        </div>
    </fieldset>
</form>