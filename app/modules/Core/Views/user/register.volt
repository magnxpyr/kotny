{{ content() }}
<div class="gray-bg">
    <div class="register-box">
        <div class="register-box-body">
            <p class="register-box-msg">Register a new membership</p>
            {{ form('user/register', 'method': 'post') }}
            <fieldset>
                <div class="form-group has-feedback">
                    {{ form.render('username') }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    {{ form.render('email') }}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    {{ form.render('password') }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

            <div class="form-group has-feedback">
                {{ form.render('repeatPassword') }}
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            {{ form.render('csrf') }}
            <div class="row">
                <div class="col-xs-8">
                    By signing up you accept the terms and privacy policy.
                </div>
                <div class="col-xs-4">
                    {{ form.render('submit') }}
                </div>
            </div>
        </fieldset>

            <div class="social-auth-links text-center">
                <p>- OR -</p>
                {{ link_to('user/login-with-facebook', '<i class="fa fa-facebook"></i>Sign up using Facebook', 'class': 'btn btn-block btn-social btn-facebook btn-flat') }}
                {{ link_to('user/login-with-google', '<i class="fa fa-google-plus"></i>Sign up using Google+', 'class': 'btn btn-block btn-social btn-google btn-flat') }}
            </div>

            {{ link_to('user/login', t._('I already have a membership')) }}
        </div>
    </div>
</div>
