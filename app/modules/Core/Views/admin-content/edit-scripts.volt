<script>
    $(function() {
        $('#wrapper-publish_up').datetimepicker({
            defaultDate: new Date()
        });
        $('#wrapper-publish_down').datetimepicker();

        initMCE(".mce textarea")
    });
</script>