var detalle_guia = [];
var detalle_guia_rc = [];

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
    $("#distrito").select2({
        placeholder: "Seleccioné Distrito"
    });
    $("#direccion_llegada").select2({
        placeholder: "Seleccioné Direccion Llegada"
    });

    $("#entidades").val(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/pedidosclientes/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
            }
        });
    });

    $("#direccion_partida").val(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/pedidosclientes/default/buscar-distrito',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_direccion: $(this).val()
            },
            success: function (response) {
                $("#distrito").html(response);
            }
        });
    });


    $("#btn-guardar").click(function () {
        $("#frm-pedidosclientes").validate({
            rules: {

                fecha: "required",
                hora: "required",
                tipo_servicio: "required",
              //  distrito: "required",
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
              //  distrito: "Seleccioné",
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
                var entidades=$("#id_entidades").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/pedidosclientes/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {

                        fecha: fecha,
                        hora: hora,
                        tipo_servicio: tipo_servicio,
                        entidades: entidades,
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
                        observacion: observacion

                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/pedidosclientes';
                    }
                });
            }
        });
    });
});



function funcionNuevaDireccion() {

    $.post(APP_URL + '/pedidosclientes/default/reg-direccion/', {}, function (resp) {
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
                            url: APP_URL + '/pedidosclientes/default/crear-direccion',
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
                                $('#direccion_partida').selectmenu('refresh',true);

                    }

                });
            });
        });
    }, 'json');
}

