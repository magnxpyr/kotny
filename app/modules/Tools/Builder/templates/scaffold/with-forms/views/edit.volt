{{ content() }}
<h1>Edit $plural$</h1>

<div class="col-md-6 col-sm-6">
    {{ form("$plural$/save", "method":"post") }}
        {{ link_to("$plural$", "Go Back") }}
        <fieldset>
            {{ hidden_field("id") }}
            $captureFields$
            <div class="form-group">
                {{ submit_button("Save", "class": "btn btn-success") }}
            </div>
        </fieldset>
    </form>
</div>