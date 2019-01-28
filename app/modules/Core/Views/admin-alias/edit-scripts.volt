<script>
     $(function () {
         $('#route_id').change(function() {
             var val = $('#route_id').val();
             var routeId = null;
             $(this.list).find('option').each(function (i, o) {
                 if (val === o.value) {
                     routeId = $(o).data('value');
                 }
             });

             if (routeId !== null) {
                 $.ajax({
                     type: 'GET',
                     url: "{{ url('admin/core/alias/route-params') }}",
                     data: {
                         routeId: routeId,
                         aliasId: $('#id').val()
                     },
                     success: function (data) {
                         $('#wrapper-params').html(data.html);
                     }
                 });
             }
         });
     });
</script>