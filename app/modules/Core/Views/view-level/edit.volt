{{ content() }}
<h1>Edit view_level</h1>

<div class="col-md-6 col-sm-6">
    {{ form("view_level/save", "method":"post", "class": "form-horizontal") }}
        {{ link_to("view_level", "Go Back") }}
        <fieldset>
            {{ hidden_field("id") }}
            
            <div class="form-group">
                <label for="name" class="control-label col-sm-4">Name</label>
                <div class="input-group">
                    {{ text_field("name", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="roles" class="control-label col-sm-4">Roles</label>
                <div class="input-group">
                    {{ text_field("roles", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Save", "class": "btn btn-success col-sm-offset-4") }}
            </div>
        </fieldset>
    </form>
</div>