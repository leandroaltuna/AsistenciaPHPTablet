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
         var codigo = $("#codigo_id").val();
      
        if (dni !== undefined && nro_local !== undefined && estado === '1') {
          
           _transfer_ua(codigo,dni,'2',nro_local);
            
            
        }
    }
    
  

    function _transfer_ua(codigo,dni,evento,nro_local) {
      
        var mensaje = $('#transf_id');
        var oOptions = {
            type: 'GET',
            url: CI.base_url + 'task/transfer_u/?key='+codigo+'&ins_numdoc='+dni+'&nro_local='+nro_local+'&evento='+evento+'&cant_ficha=0',
            
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
 

});