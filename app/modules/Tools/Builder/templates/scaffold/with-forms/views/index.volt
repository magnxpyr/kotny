{{ content() }}
<h1>Search $plural$</h1>

<div class="col-md-6 col-sm-6">
	{{ form("$plural$/search", "method":"post", "autocomplete" : "off") }}
		{{ link_to("$plural$/new", "Create $plural$") }}
		<fieldset>
			$captureFields$
			<div class="form-group">
				{{ submit_button("Search", "class": "btn btn-success") }}
			</div>
		</fieldset>
	</form>
</div>
