var detalle_guia_edit = [];
var detalle_guia_edit_rc = [];

var detalle_guia_delete = [];
var detalle_guia_delete_rc = [];

$(document).ready(function () {

    if (($("#id_tipo_entrega").val()) == 2) {
        $("#divdirec").show();
        $("#divdistrito").show();
        $("#divagente").hide();
    } else if (($("#id_tipo_entrega").val()) == 1) {

        $("#divdirec").hide();
        $("#divdistrito").hide();

        $("#divagente").show();
    }



    $("#id_tipo_entrega").change(function () {
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

    $("#remitente").select2({
        placeholder: "Seleccioné Remitente"
    });

    $("#destinatario").select2({
        placeholder: "Seleccioné Destinatario"
    });



    $("#agente").select2({
        placeholder: "Seleccioné Agente"
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



    $("#btn-actualizar-gv").click(function () {
        $("#frm-guia-venta").validate({
            rules: {

            },
            messages: {

            },
            submitHandler: function () {
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
                var id_tipo_entrega = $("#id_tipo_entrega").val();
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
                    url: APP_URL + '/guiaventas/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_guia_venta: $("#id_guia_venta").val(),
                        id_detalle_guia_venta: $("#id_detalle_guia_venta").val(),
                        id_guia_venta_destino: $("#id_guia_venta_destino").val(),
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
                        id_tipo_entrega: id_tipo_entrega,
                        agente: agente,
                        id_entidad_: id_entidad_,
                        ubigeos_gv: ubigeos_gv,
                        direccion_gv: direccion_gv,
                        observacion_gv: observacion_gv,
                        guia_cliente: guia_cliente,
                        agenteasg:agenteasg
                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/guiaventas';
                    }
                });
            }
        });
    });
});

