{{ content() }}
<div class="login-box">
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        {{ form('user/login', 'method':'post') }}
        <fieldset>
            <div class="form-group has-feedback">
                {{ form.render('username') }}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                {{ form.render('password') }}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="form-group">
                {{ form.render('captcha') }}
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox checkbox-primary">
                        {{ form.render('remember')~form.label('remember') }}
                    </div>
                </div>
                <div class="col-xs-4">
                    {{ form.render('login') }}
                </div>
            </div>
            {{ form.render('csrf') }}
            {{ end_form() }}
        </fieldset>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            {{ link_to('user/login-with-facebook', '<i class="fa fa-facebook"></i>Sign up using Facebook', 'class': 'btn btn-block btn-social btn-facebook btn-flat') }}
            {{ link_to('user/login-with-google', '<i class="fa fa-google-plus"></i>Sign up using Google+', 'class': 'btn btn-block btn-social btn-google-plus btn-flat') }}
        </div>
        {{ link_to('user/forgot-password', t._('I forgot my password')) }} <br />
        {{ link_to('user/register', t._('Register a new membership')) }}
    </div>
</div>





