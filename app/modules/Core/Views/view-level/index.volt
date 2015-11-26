{{ content() }}
<h1>Search view_level</h1>

<div class="col-md-6 col-sm-6">
    {{ form("view_level/search", "method":"post", "autocomplete" : "off", "class": "form-horizontal") }}
        {{ link_to("view_level/new", "Create view_level") }}
        <fieldset>
            
            <div class="form-group">
                <label for="id" class="control-label col-sm-4">Id</label>
                <div class="input-group">
                    {{ text_field("id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

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
                {{ submit_button("Search", "class": "btn btn-success col-sm-offset-4") }}
            </div>
        </fieldset>
    </form>
</div>
