{{ content() }}
<div class="register-box">
    <div class="register-box-body">
        <div class="title_text">Forgot your password?</div>
        <div class="title_subtext">We'll send you instructions to reset your password.</div>

        {{ form('user/forgot-password', 'method':'post') }}
            <fieldset>
                <div class="form-group has-feedback">
                    {{ form.render('email') }}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group" align="center">
                    {{ form.render('Send') }}
                </div>
            </fieldset>
        {{ end_form() }}

        <div class="container-footer">
            {{ link_to('user/login', t._('Return to Log In')) }}
        </div>
    </div>
</div>

