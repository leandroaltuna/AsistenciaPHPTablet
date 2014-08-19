
/*$(function() {
  /*$('#chkveg').multiselect({
buttonWidth :100%,
  nonSelectedText :  'Seleccione'
});*/



$(document).ready(function() {
    $('.cmbmul').multiselect({
    buttonWidth: '100%',
    

      buttonText: function(options) {
        if (options.length == 0) {
          return 'Seleccione <b class="caret"></b>';
        }

        else {
          var selected = '';
          options.each(function() {
            selected += $(this).val() + ', ';
          });
          return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
        }
      },
    });
  });



/*
$('#btnget').click(function() {
alert($('#chkveg').val());
})
*/
