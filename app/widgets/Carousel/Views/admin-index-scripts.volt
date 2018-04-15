<script>
     function addImageInput() {
         event.preventDefault();
         var random = Math.floor(Math.random() * 10000);
         var html = '<div id="wrapper-widget-images' + random + '">' +
             '<div class="input-group col-lg-offset-2">' +
             '<input type="text" id="_images' + random + '" name="_images[]" value="" class="form-control">' +
             '<span class="input-group-btn">' +
             '<button class="btn btn-default file-manager" data-input="_images' + random + '" type="button">' +
             '<i class="fa fa-folder-open"></i></button></span>' +
             '<button class="btn btn-xs" onclick="deleteImageInput(\'wrapper-widget-images' + random + '\')">' +
             '<i class="fa fa-minus-square-o"></i></button></div>' +
             '<textarea id="_text' + random + '" name="_text[]" class="form-control"></textarea>' +
             '</div>';
         $('#wrapper-widget-images').after(html);
         initMCE('#wrapper-widget-images' + random + ' #_text' + random);
     }

     function deleteImageInput(id) {
         event.preventDefault();
         $('#' + id).remove();
     }
</script>