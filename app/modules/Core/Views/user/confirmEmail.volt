{{ content() }}
<div class="register-box" style="margin-top: 25px">
    <div class="register-box-body">
        <p class="register-box-msg">Request email confirmation</p>
        <form class="form-inline">
            {{ form('user/confirm-email', 'method':'post') }}
            <fieldset>
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <span class="input-group-addon" id="email-addon">@</span>
                        {{ form.render('email', ['aria-describedby': 'email-addon']) }}
                    </div>
                </div>
                {{ form.render('csrf') }}
                <div class="form-group has-feedback">
                    {{ form.render('submit') }}
                </div>
            </fieldset>
            {{ end_form() }}
        </form>
    </div>
</div>