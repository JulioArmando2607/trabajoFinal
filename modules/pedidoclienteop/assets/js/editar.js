
$("#id_div_not").hide();


if ($("#id_cliente_v").val() == 673) {
    $("#id_div_not").show();

} else {

    $("#id_div_not").hide();

}

$(document).ready(function () {

    $("#remitente").select2({
        placeholder: "Seleccioné Remitente"
    });

    $("#destinatario").select2({
        placeholder: "Seleccioné Destinatario"
    });

    $("#cliente").select2({
        placeholder: "Seleccioné Cliente"
    });

    $("#area").select2({
        placeholder: "Seleccioné Area"
    });

    $("#id_direccion_recojo").select2({
        placeholder: "Seleccioné Direccion"
    });


    $("#btn-actualizar").click(function () {
        $("#frm-pedidosclientes").validate({
            rules: {

                fecha: "required",
                hora: "required",
                tipo_servicio: "required",

                direccion: "required",
                contacto: "required",
                area: "required",
                referencia: "required",
                telefono: "required",
                cantidad_personas: "required",
                tipo_unidad: "required",
                stoka: "required",
                fragil: "required",

            },
            messages: {
                fecha: "Ingrese datos",
                hora: "Ingrese datos",
                tipo_servicio: "Ingrese datos",

                direccion: "Ingrese datos",
                contacto: "Seleccioné",
                area: "Seleccioné",
                referencia: "Seleccioné",
                telefono: "Seleccioné",
                cantidad_personas: "Seleccioné",
                tipo_unidad: "Seleccioné",
                stoka: "Seleccioné",
                fragil: "Seleccioné",
            },
            submitHandler: function () {
                var fecha = $("#fecha").val();
                var hora = $("#hora").val();
                var tipo_servicio = $("#tipo_servicio").val();
                var distrito = $("#distrito").val();
                var remitente = $("#remitente").val();
                var direccion = $("#direccion_partida").val();
                var contacto = $("#contacto").val();
                var area = $("#area").val();
                var referencia = $("#referencia").val();
                var telefono = $("#telefono").val();
                var cantidad_personas = $("#cantidad_personas").val();
                var tipo_unidad = $("#tipo_unidad").val();
                var stoka = $("#stoka").val();
                var fragil = $("#fragil").val();
                var cantidad = $("#cantidad").val();
                var peso = $("#peso").val();
                var alto = $("#alto").val();
                var ancho = $("#ancho").val();
                var largo = $("#largo").val();
                var esta_listo = $("#esta_listo").val();
                var observacion = $("#observacion").val();
                var notificacion_ = $("#notificacion_").val();
                var notificacion_descarga = $("#notificacion_descarga").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/pedidoclienteop/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_pedido_cliente: $("#id_pedido_cliente").val(),
                        fecha: fecha,
                        hora: hora,
                        tipo_servicio: tipo_servicio,
                        remitente: remitente,
                        direccion: direccion,
                        contacto: contacto,
                        area: area,
                        referencia: referencia,
                        telefono: telefono,
                        cantidad_personas: cantidad_personas,
                        tipo_unidad: tipo_unidad,
                        stoka: stoka,
                        fragil: fragil,
                        cantidad: cantidad,
                        peso: peso,
                        alto: alto,
                        ancho: ancho,
                        largo: largo,
                        esta_listo: esta_listo,
                        observacion: observacion,
                        notificacion_:notificacion_,
                        notificacion_descarga:notificacion_descarga
                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/pedidoclienteop';
                    }
                });
            }
        });
    });
});
