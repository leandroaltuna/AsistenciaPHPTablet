/*
 *
 */


  $(document).ready(function(){
      
    if (confirm('ATENCI\u00d3N. Esta seguro que quiere realizar esta operaci\u00f3n?')) {
         ///
         
         var oOptions = {
                type: 'GET',
                url: CI.base_url + 'index/resetBD/' ,
                dataType: 'json'
            };
            var posting = $.ajax(oOptions);

            posting.done(function(response, textStatus, jqXHR) {
                var data = response.data;
              
                if (data !== null) {
                    if (data.success){
                        alert('Reseteo con exito');
                    }
                } else {
                        alert('No se pudo realizar la operaci\u00f3n');
                }
         });
            
      ///   
    }

});


