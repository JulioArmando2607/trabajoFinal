
$(document).ready(function () {
    $("#idCliente").select2({
        placeholder: "Seleccioné Cliente"
    });
    $("#idEstado").select2({
        placeholder: "Seleccioné Estado"
    });
    $("#idProvincia").select2({
        placeholder: "Seleccioné Provincia"
    });
    $("#idAgente").select2({
        placeholder: "Seleccioné Agente"
    });

    $("#btn-actualizar").click(function () {

        $("#frm-venta-edit").validate({

            rules: {
            },
            messages: {
            },
            submitHandler: function () {

                var id_registro_venta = $("#id_registro_venta").val();
                var fecha = $("#fecha").val();
                var serie = $("#serie").val();
                var factura = $("#factura").val();
                var idCliente = $("#idCliente").val();
                var valor_venta = $("#valor_venta").val();
                var igv = $("#igv").val();
                var total = $("#total").val();
                var fecha_cancelacion = $("#fecha_cancelacion").val();
                var monto_depositado = $("#monto_depositado").val();
                var monto_diferencia = $("#monto_diferencia").val();
                var idEstado = $("#idEstado").val();


                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/registroventa/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_registro_venta: id_registro_venta,
                        fecha: fecha,
                        serie: serie,
                        factura: factura,
                        idCliente: idCliente,
                        valor_venta: valor_venta,
                        igv: igv,
                        total: total,
                        fecha_cancelacion: fecha_cancelacion,
                        monto_depositado: monto_depositado,
                        monto_diferencia: monto_diferencia,
                        idEstado: idEstado,


                    },
                    beforeSend: function () {
                        $.showLoading();
                    },
                    success: function (response) {
                        notificacion('Accion realizada con exito', 'success');
                        window.location.href = APP_URL + '/registroventa';
                        datatableRegistroventa.refresh()
                    }, error: function (error) {
                        $('#btn-guardar').attr('disabled', false);
                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });

        /**/
  /*      $("#frm-venta-edit").validate({

            rules: {
            },
            messages: {
            },
            submitHandler: function () {

                var id_registro_venta = $("#id_registro_venta").val();
                var fecha = $("#fecha").val();
                var serie = $("#serie").val();
                var factura = $("#factura").val();
                var idCliente = $("#idCliente").val();
                var valor_venta = $("#valor_venta").val();
                var igv = $("#igv").val();
                var total = $("#total").val();
                var fecha_cancelacion = $("#fecha_cancelacion").val();
                var monto_depositado = $("#monto_depositado").val();
                var monto_diferencia = $("#monto_diferencia").val();
                var idEstado = $("#idEstado").val();
                var gr = $("#gr").val();
                var idProvincia = $("#idProvincia").val();
                var idAgente = $("#idAgente").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/registroventa/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_registro_venta: id_registro_venta,
                        fecha: fecha,
                        serie: serie,
                        factura: factura,
                        idCliente: idCliente,
                        valor_venta: valor_venta,
                        igv: igv,
                        total: total,
                        fecha_cancelacion: fecha_cancelacion,
                        monto_depositado: monto_depositado,
                        monto_diferencia: monto_diferencia,
                        idEstado: idEstado,
                        gr: gr,
                        idProvincia:idProvincia,
                        idAgente: idAgente

                    },
                    beforeSend: function () {
                        $.showLoading();
                    },
                    success: function (response) {
                        notificacion('Accion realizada con exito', 'success');
                        window.location.href = APP_URL + '/registroventa';
                        datatableRegistroventa.refresh()
                    }, error: function (error) {
                        $('#btn-guardar').attr('disabled', false);
                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });*/

});

