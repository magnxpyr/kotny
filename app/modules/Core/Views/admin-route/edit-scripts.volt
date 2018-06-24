<script>
     function addParamInput() {
         event.preventDefault();
         var random = Math.floor(Math.random() * 10000);
         var html = '<div class="input-group input-wrapper" id="wrapper-params' + random + '">' +
             '<span class="input-group-addon delete" onclick="deleteParamInput(\'wrapper-params' + random + '\')">' +
             '<i class="fa fa-minus"></i></span>' +
             '<input type="text" id="_paramKey' + random + '" name="_paramKey[]" value="" class="form-control img-path-input">' +
             '<span class="input-group-addon file-manager" data-input="_params' + random + '"> : </span>' +
             '<input type="text" id="_paramValue' + random + '" name="_paramValue[]" value="" class="form-control img-path-input">' +
             '</div>';
         $(html).appendTo('#wrapper-params');
     }

     function deleteParamInput(id) {
         event.preventDefault();
         $('#' + id).remove();
     }
</script>