<script>
     function addImageInput() {
         event.preventDefault();
         var random = Math.floor(Math.random() * 10000)
         var html = '<div class="input-group col-lg-offset-2" id="wrapper-widget-images' + random + '">' +
             '<input type="text" id="_images' + random + '" name="_images[]" value="" class="form-control">' +
             '<span class="input-group-btn">' +
             '<button class="btn btn-default file-manager" data-input="_images' + random + '" type="button">' +
             '<i class="fa fa-folder-open"></i></button></span>' +
             '<button class="btn btn-xs" onclick="deleteImageInput(\'wrapper-widget-images' + random + '\')">' +
             '<i class="fa fa-minus-square-o"></i></button></div>';
         $('#wrapper-widget-images').after(html);
     }

     function deleteImageInput(id) {
         event.preventDefault();
         $('#' + id).remove();
     }
</script>