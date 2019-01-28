<script>
    function flushCache() {
        $.ajax({
            type: 'POST',
            url: '{{ url('admin/core/cache/flush-cache') }}',
            success: function (data) {
                handleResponse(data);
            }
        })
    }

    function flushVoltCache() {
        $.ajax({
            type: 'POST',
            url: '{{ url('admin/core/cache/flush-volt-cache') }}',
            success: function (data) {
                handleResponse(data);
            }
        })
    }
</script>
