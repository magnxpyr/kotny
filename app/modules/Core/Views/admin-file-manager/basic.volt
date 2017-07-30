<div id="filemanager" class="filemanager"></div>

{% block scripts %}
    <script>
        $(function () {
            $("#filemanager").filemanager({
                url: "{{ url("admin/core/file-manager/connector") }}",
                languaje: "US",
                upload_max: 5,
                views: "thumbs",
                insertButton: true,
                headers: {"X-CSRF-TOKEN": "'~token~'"}
            });
        });
    </script>
{% endblock %}
