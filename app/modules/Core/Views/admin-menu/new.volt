{{ content() }}
<h1>Create menu</h1>

<div class="col-md-6 col-sm-6">
    {{ form("menu/create", "method":"post", "class": "form-horizontal") }}
        {{ link_to("menu", "Go Back") }}
        <fieldset>
            
            <div class="form-group">
                <label for="menu_type_id" class="control-label col-sm-4">Menu Of Type</label>
                <div class="input-group">
                    {{ text_field("menu_type_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="type" class="control-label col-sm-4">Type</label>
                <div class="input-group">
                    {{ text_field("type", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="title" class="control-label col-sm-4">Title</label>
                <div class="input-group">
                    {{ text_field("title", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="path" class="control-label col-sm-4">Path</label>
                <div class="input-group">
                    {{ text_field("path", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="link" class="control-label col-sm-4">Link</label>
                <div class="input-group">
                    {{ text_field("link", "size" : 30, "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="control-label col-sm-4">Status</label>
                <div class="input-group">
                    {{ text_field("status", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="parent_id" class="control-label col-sm-4">Parent</label>
                <div class="input-group">
                    {{ text_field("parent_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="level" class="control-label col-sm-4">Level</label>
                <div class="input-group">
                    {{ text_field("level", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="lft" class="control-label col-sm-4">Lft</label>
                <div class="input-group">
                    {{ text_field("lft", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="rgt" class="control-label col-sm-4">Rgt</label>
                <div class="input-group">
                    {{ text_field("rgt", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                <label for="role_id" class="control-label col-sm-4">Role</label>
                <div class="input-group">
                    {{ text_field("role_id", "size" : 30, "type": "numeric", "class": "form-control") }}
                </div>
            </div>

            <div class="form-group">
                {{ submit_button("Save", "class": "btn btn-success col-sm-offset-4") }}
            </div>
        </fieldset>
    </form>
</div>
