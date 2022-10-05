
$(document).ready(function () {
    $("#divdirec").hide();
    $("#divdistrito").hide();
    $("#divagente").show();

    $("#tipo_entrega").change(function () {
        if (($(this).val()) == 2) {
            $("#divdirec").show();
            $("#divdistrito").show();
            $("#divagente").hide();
        } else {

            $("#divdirec").hide();
            $("#divdistrito").hide();

            $("#divagente").show();
        }

    });
    
      

    $("#show").click(function () {
        $("p").show();
    });

    $("#buscar-documento-gv").on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaventas/default/buscar-e',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            data: {
                numero_documentob: $("#numero_documentob").val(),

            },
            success: function (response) {
                if (response != "") {
                    console.log(response["numero_documentob"]);
                    $("#nombrecliente").val(response["razon_social"]);
                    $("#id_entidad_").val(response["id_entidad"]);

                } else if (response == "") {
                    funcionNuevoEntidad($("#numero_documentob").val(), $("#tipo_documento").val());

                } else {
                    console.log(response);
                }
            }
        });
    });
    $("#buscar-documento-gv").on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaventas/default/buscar-e',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            data: {
                tipo_documentob: $("#tipo_documento").val(),
                numero_documentob: $("#numero_documentob").val(),

            },
            success: function (response) {
                if (response != "") {

                    $("#nombrecliente").val(response["razon_social"]);
                    $("#id_entidad_").val(response["id_entidad"]);

                } else if (response == "") {
                    
                          if($("#numero_documentob").val()==''){
                              alert('Agregar Valor')
                          }else{
                            funcionNuevoEntidades($("#numero_documentob").val(), $("#tipo_documento").val());
                          }
                   
                } else {
                    console.log(response);
                }
            }
        });
    });

    $("#agente").select2({
        placeholder: "Seleccioné Agencia"
    });
    $("#agenteas").select2({
        placeholder: "Seleccioné Agente"
    });

    $("#conductor").select2({
        placeholder: "Seleccioné Conductor"
    });

    $("#vehiculo").select2({
        placeholder: "Seleccioné Vehiculo"
    });
    $("#ubigeos_gv").select2({
        placeholder: "Seleccioné Ubigeo"
    });

    $("#numero").keyup(function () {
        var numero = $("#numero").val();
        var serie = $("#serie").val();
        if (serie == '' || serie == null) {
            notificacion('Ingrese la serie.', 'warning');
            $("#numero").val("")
            return  false;
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaventas/default/buscar-guia',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                numero: numero,
                serie: serie
            },
            success: function (response) {
                if (response.total > 0) {
                    notificacion('El número de Guia ' + numero + ' ya esta registrado.', 'warning');
                    $("#numero").val("")
                }
            }
        });
    });

    $("#btn-guardar-gv").click(function () {
        $("#frm-guia-venta").validate({
            rules: {
                serie: "required",
                numero: "required",
                fecha: "required",
                numero_documentob: "required",
                numero_documento: "required",
                nombre_destinatario:"required",
                monto_envio:"required",
                producto: "required",
                conductor:"required",
                vehiculo:"required",
                agenteas:"required",
                descripcion_producto: "required",
                cantidad: "required",
                peso: "required",
                tipo_entrega: "required",
                id_tipo_comprobante:"required",
                id_forma_pago: "required",
                tipo_documento:"required",
                tipo_dni_usuario_des :"required",
            },
            messages: {
                serie: "Ingrese datos",
                numero: "Ingrese datos",
                fecha: "Ingrese datos",
                numero_documentob: "Ingrese datos",
                numero_documento: "Ingrese datos",
                nombre_destinatario:"Ingrese datos",
                monto_envio:"Ingrese monto",
                conductor:"Seleccione conductor",
                vehiculo:"Seleccione conductor",
                agenteas:"Seleccione agentea",
                producto: "Ingrese datos",
                descripcion_producto: "Ingrese datos",
                cantidad: "Ingrese datos",
                peso: "Ingrese datos",
                tipo_entrega: "Ingrese datos",
                id_tipo_comprobante:"Seleccione Comprobante",
                id_forma_pago:"Seleccione Comprobante",
                tipo_documento:"Seleccione Comprobante",
                tipo_dni_usuario_des : "Seleccione Tipo Documento",
            },
            submitHandler: function () {
                $("#btn-guardar-gv").attr('disabled', true);
                var serie = $("#serie").val();
                var numero = $("#numero").val();
                var fecha = $("#fecha").val();
                var id_forma_pago = $("#id_forma_pago").val();
                var id_tipo_comprobante = $("#id_tipo_comprobante").val();
                var conductor = $("#conductor").val();
                var vehiculo = $("#vehiculo").val();
                var tipo_documento = $("#tipo_documento").val();
                var producto = $("#producto").val();
                var descripcion_producto = $("#descripcion_producto").val();
                var forma_envio = $("#forma_envio").val();
                var cantidad = $("#cantidad").val();
                var peso = $("#peso").val();
                var volumen = $("#volumen").val();
                var monto_envio = $("#monto_envio").val();
                var tipo_dni_usuario_des = $("#tipo_dni_usuario_des").val();
                var numero_documento = $("#numero_documento").val();
                var nombre_destinatario = $("#nombre_destinatario").val();
                var otroconsigando_gv = $("#otroconsigando_gv").val();
                var celular_destinatario = $("#celular_destinatario").val();
                var tipo_entrega = $("#tipo_entrega").val();
                var agente = $("#agente").val();
                var id_entidad_ = $("#id_entidad_").val();
                var ubigeos_gv = $("#ubigeos_gv").val();
                var direccion_gv = $("#direccion_gv").val();
                var observacion_gv = $("#observacion_gv").val();
                var guia_cliente = $("#guia_cliente").val();
                var agenteasg = $("#agenteas").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',

                    url: APP_URL + '/guiaventas/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        serie: serie,
                        numero: numero,
                        fecha: fecha,
                        id_forma_pago: id_forma_pago,
                        id_tipo_comprobante: id_tipo_comprobante,
                        conductor: conductor,
                        vehiculo: vehiculo,
                        tipo_documento: tipo_documento,
                        producto: producto,
                        descripcion_producto: descripcion_producto,
                        forma_envio: forma_envio,
                        cantidad: cantidad,
                        peso: peso,
                        volumen: volumen,
                        monto_envio: monto_envio,
                        tipo_dni_usuario_des: tipo_dni_usuario_des,
                        numero_documento: numero_documento,
                        nombre_destinatario: nombre_destinatario,
                        otroconsigando_gv: otroconsigando_gv,
                        celular_destinatario: celular_destinatario,
                        tipo_entrega: tipo_entrega,
                        agente: agente,
                        id_entidad_: id_entidad_,
                        ubigeos_gv: ubigeos_gv,
                        direccion_gv: direccion_gv,
                        observacion_gv: observacion_gv,
                        guia_cliente: guia_cliente,
                        agenteasg: agenteasg


                    },
                    beforeSend: function () {
                        $.showLoading();
                    },
                    success: function (response) {

                        window.location.href = APP_URL + '/guiaventas';
                    }, error: function (error) {
                        $('#btn-guardar-gv').attr('disabled', false);
                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });

});

function funcionNuevoEntidades(nmrDoc, tipo_documento) {

    $.post(APP_URL + '/guiaventas/default/reg-entidad/' + nmrDoc, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registrar Cliente </strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });
        $("#tipo_documento_entidad").val(tipo_documento);
        $("#tipo_entidad").val(1);

        $("#numero_documento_entidad").val(nmrDoc);

        $("#numero_documento_entidad").blur(function () {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/guiaventas/default/buscar-documento',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

                data: {
                    numero_documento_entidad: $("#numero_documento_entidad").val(),
                    tipo_documento_entidad: $("#tipo_documento_entidad").val()
                },
                success: function (response) {
                    $("#razon_social").val(response);
                }
            });

        });


        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $(document).ready(function () {
            $("#btn-guardar-entidad").click(function () {
                $("#frm-guia-venta-entidad").validate({
                    rules: {
                        tipo_entidad: "required",
                        tipo_documento_entidad: "required",
                        numero_documento_entidad: "required",
                        razon_social: "required",
                        // ubigeos: "required",
                        //  direccion: "required"

                    },
                    messages: {
                        tipo_entidad: "Por favor ingrese datos",
                        tipo_documento_entidad: "Por favor ingrese datos",
                        numero_documento_entidad: "Por favor ingrese datos",
                        razon_social: "Por favor ingrese datos",
                        //   ubigeos: "Por favor ingrese datos",
                        //  direccion: "Por favor ingrese datos",

                    },
                    submitHandler: function () {
                        var tipo_entidad = $("#tipo_entidad").val();
                        var tipo_documento_entidad = $("#tipo_documento_entidad").val();
                        var numero_documento_entidad = $("#numero_documento_entidad").val();
                        var razon_social = $("#razon_social").val();
                        var telefono = $("#telefono").val();
                        var correo = $("#correo").val();
                        var ubigeos = $("#ubigeos").val();
                        var direccion = $("#direccion").val();
                        var urbanizacion = $("#urbanizacion").val();
                        var referencias = $("#referencias").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/guiaventas/default/crear-entidad',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                tipo_entidad: tipo_entidad,
                                tipo_documento_entidad: tipo_documento_entidad,
                                numero_documento: numero_documento_entidad,
                                razon_social: razon_social,
                                telefono: telefono,
                                correo: correo,
                                ubigeos: ubigeos,
                                direccion: direccion,
                                urbanizacion: urbanizacion,
                                referencias: referencias,
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        url: APP_URL + '/guiaventas/default/buscar-e',
                                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

                                        data: {
                                            numero_documentob: $("#numero_documentob").val(),

                                        },
                                        success: function (response) {
                                            if (response != "") {
                                                console.log(response["numero_documentob"]);
                                                $("#nombrecliente").val(response["razon_social"]);
                                                $("#id_entidad_").val(response["id_entidad"]);

                                            } else if (response == "") {
                                                console.log(response);
                                            } else {
                                                console.log(response);
                                            }
                                        }
                                    });
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload()
                            }
                        });
                    }

                });
            });
        });
    }, 'json');
}





 