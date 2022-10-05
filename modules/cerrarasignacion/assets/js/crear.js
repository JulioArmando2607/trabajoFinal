var detalle_guia = [];
var detalle_guia_rc = [];

function listaAgente() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/CerrarAsignacion/default/listar-agente',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            // id_entidad: $("#entidades").val()
        },
        success: function (response) {
            $("#agente").html(response);
        }
    });
}

function listaEntidad() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/CerrarAsignacion/default/listar-entidad',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            // id_entidad: $("#entidades").val()
        },
        success: function (response) {
            $("#destinatario").html(response);
        }
    }); 
}



function calcular() {


    var largo = $("#largo").val();
    var ancho = $("#ancho").val();
    var alto = $("#alto").val();
    var pesovol = $("#pesovol").val();

//  notificacion('Ingrese la serie.', 'warning');
    pesovol = (largo * alto * ancho) / 6000;
    $("#pesovol").val(pesovol);

    $("#pesovol").prop("disabled", true);
//   return  false;
}

$("#largo").blur(function () {

    calcular()
});

$("#ancho").blur(function () {

    calcular()
});

$("#alto").blur(function () {

    calcular()
});

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

    $("#direccion_llegada").select2({
        placeholder: "Seleccioné Direccion Llegada"
    });
    $("#transportista").select2({
        placeholder: "Seleccioné"
    });



    $("#remitente").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/CerrarAsignacion/default/buscar-direccion',
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
            url: APP_URL + '/CerrarAsignacion/default/buscar-direccion',
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

    listaEntidad();
    $("#destinatario").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/CerrarAsignacion/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_llegada").html(response);
            }
        });
    });

    $("#via").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/CerrarAsignacion/default/buscar-tipo-v',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_via: $(this).val()
            },
            success: function (response) {
                $("#via_tipo").html(response);
            }
        });
    });


    /*  $("#numero").blur(function () {
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
              url: APP_URL + '/cerrarasignacion/default/buscar-guia',
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
      });*/

    listaAgente(),
        calcular()



    $("#btn-guardar").click(function () {
        $("#frm-guia-remision").validate({
            rules: {
                //serie: "required",
                //  numero: "required",
                fecha: "required",
                traslado: "required",
                via: "required",
                cliente: "required",
                agente: "required",
                remitente: "required",
                direccion_partida: "required",
                destinatario: "required",
                direccion_llegada: "required",
                conductor: "required",
                vehiculo: "required",
            },
            messages: {
                //serie: "Ingrese datos",
                // numero: "Ingrese datos",
                fecha: "Ingrese datos",
                traslado: "Ingrese datos",
                via: "Ingrese datos",
                cliente: "Seleccioné",
                agente: "Seleccioné",
                remitente: "Seleccioné",
                direccion_partida: "Seleccioné",
                destinatario: "Seleccioné",
                direccion_llegada: "Seleccioné",
                conductor: "Seleccioné",
                vehiculo: "Seleccioné",
            },
            submitHandler: function () {
                $('#btn-guardar').attr('disabled', true);
                var serie = $("#serie").val();
                var numero = $("#numero").val();
                var fecha = $("#fecha").val();
                var traslado = $("#traslado").val();
                var via = $("#via").val();
                var cliente = $("#cliente").val();
                var agente = $("#agente").val();
                var remitente = $("#remitente").val();
                var direccion_partida = $("#direccion_partida").val();
                var destinatario = $("#destinatario").val();
                var direccion_llegada = $("#direccion_llegada").val();
                var conductor = $("#conductor").val();
                var vehiculo = $("#vehiculo").val();
                var transportista = $("#transportista").val();
                var guia_remision = $("#guia_remision").val();
                var factura = $("#factura").val();
                var importe = $("#importe").val();
                var comentario = $("#comentario").val();
                var via_tipo = $("#via_tipo").val();
                var id_cliente = $("#id_cliente").val();
                var nm_solicitud = $("#solicitud").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/CerrarAsignacion/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        serie: serie,
                        numero: numero,
                        fecha: fecha,
                        traslado: traslado,
                        via: via,
                        via_tipo: via_tipo,
                        cliente: cliente,
                        agente: agente,
                        remitente: remitente,
                        id_cliente:id_cliente,
                        direccion_partida: direccion_partida,
                        destinatario: destinatario,
                        direccion_llegada: direccion_llegada,
                        conductor: conductor,
                        vehiculo: vehiculo,
                        transportista: transportista,
                        guia_remision: guia_remision,
                        factura: factura,
                        importe: importe,
                        comentario: comentario,
                        nm_solicitud: nm_solicitud,

                        detalle_guia: detalle_guia,
                        detalle_guia_rc: detalle_guia_rc
                    },
                    beforeSend: function () {
                        $.showLoading();
                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/cerrarasignacion';
                    }, error: function (error) {
                        $('#btn-guardar').attr('disabled', false);
                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });
});

var identificadorDetalle = 0;
$("#agregar-detalle").click(function () {
    let seleccion = $('select[name="producto"] option:selected').text().split('::');
    let id_producto = $("#producto").val();
    let producto = seleccion[0];
    let descripcion = seleccion[1];
    let unidad = seleccion[2];
    let cantidad = $("#cantidad").val();
    let peso = $("#peso").val();
    let alto = $("#alto").val();
    let ancho = $("#ancho").val();
    let largo = $("#largo").val();
    let volumen = $("#pesovol").val();

    detalle_guia.push({
        identificadorDetalle: identificadorDetalle++,
        id_producto: id_producto,
        producto: producto,
        descripcion: descripcion,
        unidad: unidad,
        cantidad: cantidad,
        peso: peso,
        volumen: volumen,
        alto: alto,
        ancho: ancho,
        largo: largo

    });

    mostrarTablaTemporal();
});

function eliminarTemporalDetalleGuia(id) {
    var data = detalle_guia.filter(function (item) {
        return +item.identificadorDetalle !== id;
    });

    detalle_guia = data;

    mostrarTablaTemporal();
}

function mostrarTablaTemporal() {
    let temporalDetalleGuia = "";

    detalle_guia.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.producto + '</td><td>' + item.descripcion + '</td><td>' + item.unidad + '</td><td>' + item.cantidad + '</td><td>' + item.peso + '</td><td>' + item.volumen + '</td><td><i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuia(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-guia").html(temporalDetalleGuia);
}

/////////////////////////////////////////////////////////////

var identificadorDetalleRC = 0;
$("#agregar-detalle-rc").click(function () {
    let grs = $("#grserie").val();
    let gr = $("#gr").val();
    let ft = $("#ft").val();
    let oc = $("#oc").val();
    let id_tipo_carga = $("#tipo_carga").val();
    let tipo_carga = $('select[name="tipo_carga"] option:selected').text()
    let descripcion = $("#descripcion").val();

    detalle_guia_rc.push({
        identificadorDetalle: identificadorDetalleRC++,
        grs: grs,
        gr: gr,
        ft: ft,
        oc: oc,
        id_tipo_carga: id_tipo_carga,
        tipo_carga: tipo_carga,
        descripcion: descripcion,
    });

    mostrarTablaTemporalRC();
});

function eliminarTemporalDetalleGuiaRC(id) {
    var data = detalle_guia_rc.filter(function (item) {
        return +item.identificadorDetalle !== id;
    });

    detalle_guia_rc = data;

    mostrarTablaTemporalRC();
}

function mostrarTablaTemporalRC() {
    let temporalDetalleGuia = "";

    detalle_guia_rc.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.grs + '</td><td>' + item.gr + '</td><td>' + item.ft + '</td><td>' + item.oc + '</td><td>' + item.tipo_carga + '</td><td>' + item.descripcion + '</td><td><i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuiaRC(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-guia-rc").html(temporalDetalleGuia);
}

function  funcionAgregarAgente() {

    $.post(APP_URL + '/CerrarAsignacion/default/reg-agente/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Agente</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar-agente").click(function () {
                $("#form-agente-reg").validate({
                    rules: {

                        cuenta: "required",
                        agente: "required",

                    },
                    messages: {

                        cuenta: "Por favor ingrese datos",
                        agente: "Por favor ingrese datos",

                    },
                    submitHandler: function () {

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/CerrarAsignacion/default/create-agente',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                cuenta: $("#cuenta_ag").val(),
                                agente: $("#agente_nm").val(),

                            },
                            success: function (response) {
                                bootbox.hideAll();

                                if (response) {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        url: APP_URL + '/CerrarAsignacion/default/buscar-agente',
                                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                        data: {
                                            id_agente: response
                                            // id_entidad: $("#entidades").val()
                                        },
                                        success: function (response) {
                                            //   listaAgente();
                                            $("#agente").html(response);

                                        }
                                    });

                                    notificacion('Accion realizada con exito' + response, 'success');

                                } else {
                                    listaAgente();
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload()
                                //      $("#agente").html();


                            }

                        });

                    }
                });
            });
        });
    }, 'json');

}

function  funcionCargarED(response) {

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/CerrarAsignacion/default/buscar-entidad',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            id_entidad: response
        },
        success: function (response) {
            $("#destinatario").html(response);
            $("#destinatario").val(function () {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/CerrarAsignacion/default/buscar-direccion',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_entidad: $(this).val()
                    },
                    success: function (response) {
                        $("#direccion_llegada").html(response);
                    }
                });
            });
            $("#destinatario").html(response);
        }
    });
}

function funcionAgregarDestinatario() {

    $.post(APP_URL + '/CerrarAsignacion/default/reg-entidad', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registrar Cliente </strong></h2>",
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

        $("#numero_documento_entidad").blur(function () {
            var numero = $("#numero_documento_entidad").val();
            //   var serie = $("#serie").val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/CerrarAsignacion/default/buscar-numero-doc',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    numero: numero,

                },
                success: function (response) {
                    if (response.total > 0) {
                        notificacion('El número de Documento ' + numero + ' ya esta registrado.', 'warning');
                        $("#numero_documento_entidad").val("")
                    }
                }
            });
        });
        $(document).ready(function () {
            $("#btn-guardar-entidad").click(function () {
                $("#frm-guia-venta-entidad").validate({
                    rules: {
                        //tipo_entidad: "required",
                        tipo_documento_entidad: "required",
                        numero_documento_entidad: "required",
                        razon_social: "required",
                        ubigeos: "required",
                        direccion: "required"

                    },
                    messages: {
                        //  tipo_entidad: "Por favor ingrese datos",
                        tipo_documento_entidad: "Por favor ingrese datos",
                        numero_documento_entidad: "Por favor ingrese datos",
                        razon_social: "Por favor ingrese datos",
                        ubigeos: "Por favor ingrese datos",
                        direccion: "Por favor ingrese datos",

                    },
                    submitHandler: function () {
                        var tipo_entidad = 1;
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
                            url: APP_URL + '/CerrarAsignacion/default/crear-entidad',
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
                                    funcionCargarED(response);
                                    notificacion('Accion realizada con exito' + response, 'success');


                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }

                            }
                        });
                    }

                });
            });
        });
    }, 'json');
}


function funcionAgregarTranportista() {

    $.post(APP_URL + '/CerrarAsignacion/default/reg-transportista/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Agente</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#ubigeos").select2({
            placeholder: "Seleccioné"
        });

        $("#numero_documento").blur(function () {
            var numero = $("#numero_documento").val();
            //   var serie = $("#serie").val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/CerrarAsignacion/default/buscar-numero-doc-trs',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    numero: numero,

                },
                success: function (response) {
                    if (response.total > 0) {
                        notificacion('El número de Documento ' + numero + ' ya esta registrado.', 'warning');
                        $("#numero_documento").val("")
                    }
                }
            });
        });

        $(document).ready(function () {
            $("#btn-guardar-transportista").click(function () {
                $("#form-transportista-reg").validate({
                    rules: {
                        tipo_documento: "required",
                        numero_documento: "required",
                        razon_social: "required",

                        ubigeos: "required",
                        direccion: "required",

                    },
                    messages: {

                        tipo_documento: "Por favor ingrese datos",
                        numero_documento: "Por favor ingrese datos",
                        razon_social: "Por favor ingrese datos",

                        ubigeos: "Seleccione",
                        direccion: "Por favor ingrese datos"

                    },
                    submitHandler: function () {

                        var tipo_documento = $("#tipo_documento").val();
                        var numero_documento = $("#numero_documento").val();
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
                            url: APP_URL + '/CerrarAsignacion/default/crear-transportista',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                tipo_documento: tipo_documento,
                                numero_documento: numero_documento,
                                razon_social: razon_social,
                                telefono: telefono,
                                correo: correo,
                                ubigeos: ubigeos,
                                direccion: direccion,
                                urbanizacion: urbanizacion,
                                referencias: referencias,

                            },
                            success: function (response) {



                                $.ajax({
                                    type: "POST",
                                    dataType: 'json',
                                    url: APP_URL + '/CerrarAsignacion/default/buscar-transportista',
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    data: {
                                        id_transportista: response
                                    },
                                    success: function (response) {
                                        $("#transportista").html(response);


                                    }
                                });
                                notificacion('Registrado' + response, 'success')
                                bootbox.hideAll();


                            }

                        });

                    }
                });
            });
        });
    }, 'json');

}

  