/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function _transfer(event) {
     
        if (confirm('ATENCI\u00d3N. Esta seguro que quiere realizar esta operaci\u00f3n?')) {
            var btn = $("#btnEnviar_id");
            btn.button('loading');
            var oOptions = {
                type: 'GET',
                url: CI.base_url + 'task/transfer/' + event,
                dataType: 'json',
                beforeSend: function() {
                    btn.button('loading');
                }
            };
            var posting = $.ajax(oOptions);

            posting.done(function(response, textStatus, jqXHR) {
                var data = response.data;
                if (data !== null) {
                    if (data.success) {
                        alert('Actualizaci\u00f3n exitosa');
                    }
                } else {
                    alert('No se pudo transferir el registro');
                }
               // btn.button('reset');
            }).fail(function(response, textStatus, jqXHR) {
                alert('Ocurrio un error inesperado');
               //alert(jqXHR);
            //    btn.button('reset');
            });
        }
    }