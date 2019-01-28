{{ content() }}
<div class="gray-bg">
    <div class="register-box">
        <div class="register-box-body">
            {{ form('user/reset-password', 'method':'post') }}
                <fieldset>
                    <div class="form-group has-feedback">
                        {{ form.label('password', ['class': 'control-label']) }}
                        {{ form.render('password') }}
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>

                    <div class="form-group has-feedback">
                        {{ form.label('repeatPassword', ['class': 'control-label']) }}
                        {{ form.render('repeatPassword') }}
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    {{ form.render('csrf') }}
                    <div class="form-group">
                        {{ form.render('submit') }}
                    </div>
                </fieldset>
            {{ end_form() }}
        </div>
    </div>
</div>