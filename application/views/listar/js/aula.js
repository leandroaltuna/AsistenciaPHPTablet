/*
 *
 */


$(document).ready(function(){
    $("#ins_numdoc").focus();
  
   
});


$('#paulas_id').change(function()
 {
     
     $('#frm1').submit();
 });
$("#ins_numdoc").focus();
/*
$(function() {
    transfer_ua();

    function transfer_ua() {
        
       
        var dni = $("#ins_numdoc_id").val();
        var nro_local = $("#nro_local_id").val();
        if (dni !== undefined && nro_local !== undefined) {
            var data = {'ins_numdoc': dni, 'nro_local': nro_local};
            _transfer_ua(data);
            
            
        }
    }
    
  

    function _transfer_ua(_data) {
      
        var mensaje = $('#transf_id');
        var oOptions = {
            type: 'POST',
            url: CI.base_url + 'aula/transfer/',
            data: _data,
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


   
   
 //   var vjson = $('#vjson').val();
 //   performSubmitHandler('#frmdirector_id', vjson);

});*/