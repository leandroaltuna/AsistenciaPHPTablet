/*
 *
 */


$(document).ready(function(){
    $("#ins_numdoc").focus();
    
  
   
});



$(function() {
    transfer_ua();

    function transfer_ua() {
        
       
        var dni = $("#ins_numdoc_id").val();
        var nro_local = $("#nro_local_id").val();
        var estado = $("#estado_id").val();
      
        if (dni !== undefined && nro_local !== undefined && estado === '1') {
          
           _transfer_ua(dni,'4');
            
            
        }
    }
    
  

    function _transfer_ua(dni,evento) {
      
        var mensaje = $('#transf_id');
        var oOptions = {
            type: 'GET',
            url: CI.base_url + 'task/transfer_u/?key='+dni+'&evento='+evento,
            dataType: 'json',
            beforeSend: function() {
                
                
                mensaje.parent().addClass('alert-success');
                mensaje.text('Sincronizando con monitoreo-Sede Central...');
            }
        };
        var posting = $.ajax(oOptions);
        
        posting.done(function(response, textStatus, jqXHR) {
            var data = response.data;
        
   }); }


   
   $("#ins_numdoc").focus();
 //   var vjson = $('#vjson').val();
 //   performSubmitHandler('#frmdirector_id', vjson);

});