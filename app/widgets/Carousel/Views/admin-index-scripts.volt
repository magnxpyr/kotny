<script>
     function addImageInput() {
         event.preventDefault();
         var random = Math.floor(Math.random() * 10000)
         var html = '<div class="input-group input-wrapper" id="wrapper-widget-images' + random + '">' +
             '<div class="input-group"><span class="input-group-addon delete" onclick="deleteImageInput(\'wrapper-widget-images' + random + '\')">' +
             '<i class="fa fa-minus"></i></span>' +
             '<input type="text" id="_images' + random + '" name="_images[]" value="" class="form-control img-path-input">' +
             '<span class="input-group-addon file-manager" data-input="_images' + random + '">' +
             '<i class="fa fa-folder-open"></i></span></div>' +
             '<textarea id="_text' + random + '" name="_text[]" class="form-control"></textarea>' +
             '</div>';
         $(html).appendTo('#wrapper-widget-images');
         initMCE('#wrapper-widget-images' + random + ' #_text' + random);
     }

     function deleteImageInput(id) {
         event.preventDefault();
         $('#' + id).remove();
     }
</script>