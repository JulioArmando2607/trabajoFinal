 $("#id_div_not").hide();
    
    $("#entidades_cliente").change(function () {
        if (($(this).val()) == 673) {
            $("#id_div_not").show();
     
        } else {

            $("#id_div_not").hide();
            
        }


    });
$(document).ready(function () {

    $("#remitente").select2({
        placeholder: "Seleccioné Remitente"
    });

    $("#destinatario").select2({
        placeholder: "Seleccioné Destinatario"
    });

    $("#entidades").select2({
        placeholder: "Seleccioné Remitente"
    });

    $("#entidades_cliente").select2({
        placeholder: "Seleccioné Cliente"
    });


    $("#agente").select2({
        placeholder: "Seleccioné Agente"
    });

    $("#conductor").select2({
        placeholder: "Seleccioné Conductor"
    });

    $("#vehiculo").select2({
        placeholder: "Seleccioné Vehiculo"
    });

    $("#direccion_partida").select2({
        placeholder: "Seleccioné Direccion Partida"
    });

    $("#area").select2({
        placeholder: "Seleccioné Area"
    });


    $("#remitente").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/atenderasignacion/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
            }
        });
    });

    if ($("#remitente").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/atenderasignacion/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $("#remitente").val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
                var id_direccion_partida = $("#id_direccion_partida").val();
                $("#direccion_partida option[value='" + id_direccion_partida + "']").attr("selected", true);
            }
        });
    }


    $("#entidades").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/pedidoclienteop/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
            }
        });
    });


    $("#btn-guardarop").click(function () {
        $("#frm-pedidoclienteop").validate({
            rules: {

                fecha: "required",
                hora: "required",
                tipo_servicio: "required",
                //  distrito: "required",
                direccion: "required",
                //contacto: "required",
               // area: "required",
                //referencia: "required",
               // telefono: "required",
                //cantidad_personas: "required",
                tipo_unidad: "required",
               // stoka: "required",
               // fragil: "required",

            },
            messages: {
                fecha: "Ingrese datos",
                hora: "Ingrese datos",
                tipo_servicio: "Seleccioné",
                //  distrito: "Seleccioné",
                direccion: "Seleccioné",
               // contacto: "Ingrese datos",
                //area: "Seleccioné",
                // referencia: "Seleccioné",
                //telefono: "Ingrese datos",
                //cantidad_personas: "Seleccioné",
                tipo_unidad: "Seleccioné",
                //stoka: "Seleccioné",
                //fragil: "Seleccioné",
            },
            submitHandler: function () {
                var fecha = $("#fecha").val();
                var hora = $("#hora").val();
                var tipo_servicio = $("#tipo_servicio").val();
                //var distrito = $("#distrito").val();
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
                var entidades = $("#entidades").val();
                var notificacion_ = $("#notificacion_").val();
                var notificacion_descarga = $("#notificacion_descarga").val();
        var entidades_cliente = $("#entidades_cliente").val();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/pedidoclienteop/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        fecha: fecha,
                        hora: hora,
                        tipo_servicio: tipo_servicio,
                        entidades: entidades,
                         entidades_cliente: entidades_cliente,
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



function funcionNuevaDireccion() {

    $.post(APP_URL + '/pedidoclienteop/default/reg-direccion/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registrar Direccion </strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $(document).ready(function () {
            $("#btn-guardar-direccion").click(function () {
                $("#form-direcciones").validate({
                    rules: {

                        ubigeos: "required",
                        direccion: "required",
                        urbanizacion: "required",
                        referencias: "required",

                    },
                    messages: {

                        ubigeos: "Por favor ingrese datos",
                        direccion: "Por favor ingrese datos",
                        urbanizacion: "Por favor ingrese datos",
                        referencias: "Por favor ingrese datos",

                    },
                    submitHandler: function () {
                        var entidad = $("#entidad").val();
                        var ubigeos = $("#ubigeos").val();
                        var direccion = $("#direccion").val();
                        var urbanizacion = $("#urbanizacion").val();
                        var referencias = $("#referencias").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/pedidoclienteop/default/crear-direccion',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                entidad: entidad,
                                ubigeos: ubigeos,
                                direccion: direccion,
                                urbanizacion: urbanizacion,
                                referencias: referencias
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    // datatable.reload();
                                    notificacion('Accion realizada con exito', 'success');

                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }

                                // datatable.reload()
                            }
                        });
                        $('#direccion_partida').selectmenu('refresh', true);

                    }

                });
            });
        });
    }, 'json');
}

