{{ content() }}
<h1>Search role</h1>

<div class="col-md-6 col-sm-6">
    {{ form("role/search", "method":"post", "autocomplete" : "off", "class": "form-horizontal") }}
        {{ link_to("role/new", "Create role") }}
        <fieldset>
            
            <div class="form-group">
                <label for="id" class="control-label col-sm-4">Id</label>
                <div class="input-group">
                    {{ text_field("id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="parent_id" class="control-label col-sm-4">Parent</label>
                <div class="input-group">
                    {{ text_field("parent_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="control-label col-sm-4">Name</label>
                <div class="input-group">
                    {{ text_field("name", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="control-label col-sm-4">Description</label>
                <div class="input-group">
                    {{ text_field("description", "size" : 30, "type": "date", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Search", "class": "btn btn-success col-sm-offset-4") }}
            </div>
        </fieldset>
    </form>
</div>
