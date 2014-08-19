 /*inicializazando elementos cuestionario1*/
 $("#txt40").hide();
 $("#txt41").hide();
 $("#txt46").hide();
 $("#txt47").hide();
 $("#txt49").hide();
 $("#txt52").hide();
 $("#txt53").hide();
 $("#txt59").hide();
 $("#txt60").hide();
 $("#txt62").hide();
 $("#txt64").hide();
 $("#txt67").hide();
 $("#txt70").hide();
 $("#txt73").hide();
 $("#txt74").hide();
 $("#txt75").hide();
 $("#btnpas48").hide();
 $("#btnpas54").hide();
 $("#btnpas63").hide();
 $("#btnpas78").hide();
 $("#btnpas14").hide();
 
 
 /*inicializazando elementos*/
  $("#txt114").hide();
  $("#btnpas107").hide();
  $("#btnpas111").hide();
  $("#txt112").hide();
  $("#btnpas201").hide();
  $("#btnpas202").hide();
  $("#btnpas206").hide();

$("#1text26p6").hide();
$("#2text26p6").hide();
$("#3text26p6").hide();



  

/*----------------------------mmmenu------------------------------------------*/
  $(function() {
        $('#menu').mmenu({
          slidingSubmenus: false,
          modal:false
        });
      });
/*----------------------------fin mmmenu--------------------------------------*/  




/*---------------------- TEXTBOX OCULTO---------------------------------------*/

    $('#cmb114').change(function(){
      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 4){
        $("#txt114").show();
      }else{
        $("#txt114").hide();
      }
    });

    $('#cmb112').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt112").show();
      }else{
        $("#txt112").hide();
      }
    });
/*-------------------------FIN DE TEXTBOX OCULTO ----------------------------*/



/*---------------------- BOTON PASE OCULTO---------------------------------------*/
   

$('#2rb107').change(function(){
        $("#btnpas107").show();
});

   
$('#1rb107').change(function(){
        $("#btnpas107").hide();
});


$('#1rb111').change(function(){
        $("#btnpas111").show();
});

   
$('#2rb111').change(function(){
        $("#btnpas111").hide();
});

$('#1rb201').change(function(){
        $("#btnpas201").hide();
});

   
$('#2rb201').change(function(){
        $("#btnpas201").show();
});

$('#1rb202').change(function(){
        $("#btnpas202").hide();
});

   
$('#2rb202').change(function(){
        $("#btnpas202").show();
});


$("input[name^='opt206']" ).change(function(){
if($(this).val()=="2"){
  $("#btnpas206").show();
}else{
  $("#btnpas206").hide();
}
});


 /*inicializazando elementos cuestionario1*/
  $("input[name^='opt42']").change(function(){
if($(this).val()=="2"){
  $("#btnpas42").show();
}else{
  $("#btnpas42").hide();
}
});


 $("input[name^='opt48']").change(function(){
if($(this).val()=="2"){
  $("#btnpas48").show();
}else{
  $("#btnpas48").hide();
}
});

  $("input[name^='opt54']").change(function(){
if($(this).val()=="1"){
  $("#btnpas54").show();
}else{
  $("#btnpas54").hide();
}
});

    $("input[name^='opt63']").change(function(){
if($(this).val()=="2"){
  $("#btnpas63").show();
}else{
  $("#btnpas63").hide();
}
});

 $("input[name^='opt78']").change(function(){
if($(this).val()=="2"){
  $("#btnpas78").show();
}else{
  $("#btnpas78").hide();
}
});

 $("input[name^='opt14']").change(function(){
if($(this).val()=="2"){
  $("#btnpas14").show();
}else{
  $("#btnpas14").hide();
}
});

 

   





$('#cmb40').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt40").show();
      }else{
        $("#txt40").hide();
      }
});


$('#cmb41').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt41").show();
      }else{
        $("#txt41").hide();
      }
});




$('#cmb46').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt46").show();
      }else{
        $("#txt46").hide();
      }
});

$('#cmb47').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt47").show();
      }else{
        $("#txt47").hide();
      }
});

$('#cmb49').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt49").show();
      }else{
        $("#txt49").hide();
      }
});


$('#cmb52').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 6){
        $("#txt52").show();
      }else{
        $("#txt52").hide();
      }
});

$('#cmb53').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt53").show();
      }else{
        $("#txt53").hide();
      }
});

$('#cmb59').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt59").show();
      }else{
        $("#txt59").hide();
      }
});

$('#cmb60').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 6){
        $("#txt60").show();
      }else{
        $("#txt60").hide();
      }
});

$('#cmb62').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt62").show();
      }else{
        $("#txt62").hide();
      }
});


$('#cmb64').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt64").show();
      }else{
        $("#txt64").hide();
      }
});

$('#cmb67').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 6){
        $("#txt67").show();
      }else{
        $("#txt67").hide();
      }
});


$('#cmb70').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt70").show();
      }else{
        $("#txt70").hide();
      }
});

$('#cmb74').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt74").show();
      }else{
        $("#txt74").hide();
      }
});


$('#cmb75').change(function(){

      var $selectedOption = $(this).find('option:selected').val();

      if($selectedOption == 96){
        $("#txt75").show();
      }else{
        $("#txt75").hide();
      }
});


/*-------------------------FIN DE PASE OCULTO ----------------------------*/



/*radiobutton matriz*/

 $("input[name^='opt14']").change(function(){
if($(this).val()=="2"){
  $("#rbmatriz").show();
}else{
  $("#rbmatriz").hide();
}
});

 $("input[name^='rb26p6']").change(function(){

if($(this).val()=="1"){

  $("#1text26p6").show();
  $("#2text26p6").show();
  $("#3text26p6").show();



}else{
    $("#1text26p6").hide();
  $("#2text26p6").hide();
  $("#3text26p6").hide();
}
});



/*siguiente*/

function siguiente1(){

  location.href="./matriz-discapacidad.html";
}


    

