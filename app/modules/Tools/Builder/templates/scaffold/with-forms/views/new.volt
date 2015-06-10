{{ content() }}
<h1>Create $plural$</h1>

<div class="col-md-6 col-sm-6">
    {{ form("$plural$/create", "method":"post") }}
        {{ link_to("$plural$", "Go Back") }}
        <fieldset>
            $captureFields$
            <div class="form-group">
                {{ submit_button("Save", "class": "btn btn-success") }}
            </div>
        </fieldset>
    </form>
</div>
