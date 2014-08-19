/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function fn_enable(ctrl) {
    $(ctrl).removeAttr("disabled");
}

function fn_disable(ctrl) {
    $(ctrl).val('').attr("disabled", "disabled");
}


function fn_enread(ctrl) {
    $(ctrl).removeAttr("readonly");
}

function fn_disread(ctrl) {
    $(ctrl).val('').attr("readonly", "readonly");
}

$.validator.addMethod("alfanumerico", function(value, element) {
    return this.optional(element) || /^[a-zñA-ZÑ0-9\sáéíóúÁÉÍÓÚ]*$/i.test(value);
}, "Ingrese un alfaNumerico valido"
        );

$.validator.addMethod("alfabeto", function(value, element) {
    return this.optional(element) || /^[a-zñA-ZÑ\sáéíóúÁÉÍÓÚ]*$/i.test(value);
}, "Ingrese un alfabeto valido"
        );


$.validator.addMethod("puerta", function(value, element) {
    return this.optional(element) || /(^[0-9]{4}$,^[a-zA-Z]{1}$,^[0-9]{4}[a-zA-Z]{1}$,^[SN]$)/i.test(value);
}, "Ingrese una puerta valida"
        );

$.validator.addMethod("rangeopcional", function(value, element, options) {
    var error = false;

    var valor_0 = options[0];
    var valor_1 = options[1];
    var valor_2 = options[2];

    if (value == '') {
        error = true;
    }
    else if (valor_2 == value) {
        error = true;
    } else if (value >= valor_0 && value <= valor_1) {
        error = true
    }

    return error;
}, "Ingrese un dato valido"
        );

$.validator.addMethod("regExp", function(value, element, options) {

    var filtroReg = new RegExp(options);
    var pasaFiltro = filtroReg.test(value);

    return (this.optional(element) || pasaFiltro);

}, "Ingrese un dato valido"
        );

$('input[type=number]').keypress(function(e) {
//    if(isNaN(this.value)){
//        this.value = null;
//        e.preventDefault();
//        return false;
//    }
    if ($.hasData(this)) {
        var nchars = parseInt($(this).data('length')) - 1;
        if (this.value.length > nchars) {
            this.value = this.value.slice(0, nchars);
        } else if (this.value === '') {
            this.value = this.value.slice(0, nchars);
        }
    }
});

function performSubmitHandler(_form,_json){
    var _vjson = $.parseJSON(_json);
    var _func = _vjson['submitHandler'];
    var _funce = _vjson['invalidHandler'];
    if(_func!==undefined){
        var func = window[_func];
        if( func && typeof func === "function" ) {
            _vjson['submitHandler'] = function (form){func(form);};
        }
    }
    if(_funce!==undefined){
        var funce = window[_funce];
        if( funce && typeof funce === "function" ) {
            _vjson['invalidHandler'] = function (form, validator){funce(form, validator);};
        }
    }
    console.log(_vjson);
    $(_form).validate(_vjson);
}