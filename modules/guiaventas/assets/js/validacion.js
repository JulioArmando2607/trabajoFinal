$("#id_tipo_comprobante").change(function () {

    if (($("#id_forma_pago").val()) == 5 && $(this).val() == 1) {
        $("#tipo_dni_usuario_des option").prop('disabled', false);
        $("#tipo_dni_usuario_des option[value=2]").prop('disabled', true);
    } else if (($("#id_forma_pago").val()) == 5 && $(this).val() == 2) {
        $("#tipo_dni_usuario_des option").prop('disabled', true);
        $("#tipo_dni_usuario_des option[value=2]").prop('disabled', false);
    }

});

$("#id_forma_pago").change(function () {
    if (($(this).val()) == 5 && $("#id_tipo_comprobante").val() == 1) {
        $("#tipo_dni_usuario_des option").prop('disabled', false);
        $("#tipo_dni_usuario_des option[value=2]").prop('disabled', true);
    } else if (($(this).val()) == 5 && $("#id_tipo_comprobante").val() == 2) {
        $("#tipo_dni_usuario_des option").prop('disabled', true);
        $("#tipo_dni_usuario_des option[value=2]").prop('disabled', false);
    } else {

        $("#tipo_dni_usuario_des option").prop('disabled', false);
        $("#tipo_dni_usuario_des").val('');
    }
});


///


$("#id_tipo_comprobante").change(function () {

    if (($("#id_forma_pago").val()) != 5 && $(this).val() == 1) {
        $("#tipo_documento option").prop('disabled', false);
        $("#tipo_documento option[value=2]").prop('disabled', true);
        $("#tipo_documento").val('');
    } else if (($("#id_forma_pago").val()) != 5 && $(this).val() == 2) {
        $("#tipo_documento option").prop('disabled', true);
        $("#tipo_documento option[value=2]").prop('disabled', false);
        $("#tipo_documento").val();
    }
    else {
        $("#tipo_documento").val('');
    }

});

$("#id_forma_pago").change(function () {
    if (($(this).val()) != 5 && $("#id_tipo_comprobante").val() == 1) {
        $("#tipo_documento option").prop('disabled', false);
        $("#tipo_documento option[value=2]").prop('disabled', true);
    } else if (($(this).val()) != 5 && $("#id_tipo_comprobante").val() == 2) {
        $("#tipo_documento option").prop('disabled', true);
        $("#tipo_documento option[value=2]").prop('disabled', false);

    } else {

        $("#tipo_documento option").prop('disabled', false);
        $("#tipo_documento").val('');
    }
});





$("#numero_documento").blur(function () {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/guiaventas/default/buscar-documento',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        data: {
            numero_documento_entidad: $("#numero_documento").val(),
            tipo_documento_entidad: $("#tipo_dni_usuario_des").val()
        },
        success: function (response) {
            $("#nombre_destinatario").val(response);
        }
    });
});


contenido_textarea = ""
total_valor = 0;
num_caracteres_permitidos = 0

$("#tipo_documento").change(function () {
    $("#numero_documentob").val('');
    $("#nombrecliente").val('');
    if (($(this).val()) == 2) {
        cuenta()
      
        num_caracteres_permitidos = 11;

    } else {
        cuenta()
        num_caracteres_permitidos = 8;

    }


}); 
numerodoc = $("#numero_documentob").val();
function valida_longitud() {
    cuenta()


    if (total_valor > num_caracteres_permitidos) {
        cuenta()
        $("#numero_documentob").val('');
    } else {
        cuenta()
        contenido_textarea = numerodoc
    }


}
function cuenta() {
    $("#numero_documentob").keyup(function () {
        total_valor = ($(this).val()).length;
     
    });

}

///////////////////////////////////////

contenido_textarea2 = ""
total_valor2 = 0;
num_caracteres_permitidos2 = 0
numerodoc2 = $("#numero_documento").val();

$("#tipo_dni_usuario_des").change(function () {
    $("#numero_documento").val('');
    $("#nombre_destinatario").val('');
  
    if (($(this).val()) == 2) {
        cuenta2()
      
        num_caracteres_permitidos2 = 11;

    } else {
        cuenta2()
        num_caracteres_permitidos2 = 8;

    }


}); 

function valida_longitud2() {
    cuenta2()


    if (total_valor2 > num_caracteres_permitidos2) {
        cuenta2()
        $("#numero_documento").val('');
    } else {
        cuenta2()
        contenido_textarea2 = numerodoc2
    }


}
function cuenta2() {
    $("#numero_documento").keyup(function () {
        total_valor2 = ($(this).val()).length;
      
    });

}




