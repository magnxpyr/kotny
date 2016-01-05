{{ content() }}
{{ form('user/confirm-email', 'method':'post') }}
    <fieldset>
        <div class="form-group form-max-size">
            <div class="input-group">
                <span class="input-group-addon" id="email-addon">@</span>
                {{ render('email', ['aria-describedby': 'email-addon']) }}
            </div>
        </div>
        <div class="form-group">
            {{ render('submit') }}
        </div>
    </fieldset>
{{ end_form() }}
