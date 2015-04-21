
{{ content() }}

<div align="right">
    {{ link_to("users/new", "Create users") }}
</div>

{{ form("users/search", "method":"post", "autocomplete" : "off") }}

<div align="center">
    <h1>Search users</h1>
</div>

<table>
    <tr>
        <td align="right">
            <label for="id">Id</label>
        </td>
        <td align="left">
            {{ text_field("id", "type" : "numeric") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="role_id">Role</label>
        </td>
        <td align="left">
            {{ text_field("role_id", "type" : "numeric") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="username">Username</label>
        </td>
        <td align="left">
            {{ text_field("username", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="password">Password</label>
        </td>
        <td align="left">
                {{ text_field("password") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="crypt">Crypt</label>
        </td>
        <td align="left">
                {{ text_field("crypt") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="name">Name</label>
        </td>
        <td align="left">
            {{ text_field("name", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="email">Email</label>
        </td>
        <td align="left">
            {{ text_field("email", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="created_at">Created</label>
        </td>
        <td align="left">
            {{ text_field("created_at", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="active">Active</label>
        </td>
        <td align="left">
                {{ text_field("active") }}
        </td>
    </tr>

    <tr>
        <td></td>
        <td>{{ submit_button("Search") }}</td>
    </tr>
</table>

</form>