{{ content() }}
<h1>Edit access_list</h1>

<div class="col-md-6 col-sm-6">
    {{ form("access_list/save", "method":"post") }}
        {{ link_to("access_list", "Go Back") }}
        <fieldset>
            {{ hidden_field("id") }}
            
            <div class="form-group">
                <label for="roles_name" class="control-label">Roles Of Name</label>
                <div class="input-group">
                    {{ text_field("roles_name", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="resources_name" class="control-label">Resources Of Name</label>
                <div class="input-group">
                    {{ text_field("resources_name", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="access_name" class="control-label">Access Of Name</label>
                <div class="input-group">
                    {{ text_field("access_name", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="allowed" class="control-label">Allowed</label>
                <div class="input-group">
                    {{ text_field("allowed", "type" : "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Save", "class": "btn btn-success") }}
            </div>
        </fieldset>
    </form>
</div>