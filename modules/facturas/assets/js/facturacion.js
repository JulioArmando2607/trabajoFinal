function funcionFacturacion(id) {
    $.post(APP_URL + '/facturas/default/get-modal-facturacion/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>FACTURACION</strong></h2>",
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


        $("#btn-factura").click(function () {
            $("#frm-facturacion").validate({

                submitHandler: function () {


                    var id_ventas_factura = $("#id_ventas_factura").val();
                    var precio_unitario = $("#precio_unitario").val();
                    var igv = $("#igv").val();
                    var monto_envio = $("#monto_envio").val();


                    // $.showLoading();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/facturas/default/factura',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                            id_ventas_factura: id_ventas_factura,
                            precio_unitario:precio_unitario,
                            igv:igv,
                            monto_envio:monto_envio

                        },

                        success: function (response) {

                            //   $.hideLoading();
                            if (response == 1) {
                                notificacion('Enviado', 'success');
                                bootbox.hideAll();

                                //   window.location.href = "guiaventas/default/imprimir-factura/" +response ;


                            } else if (response == 2) {
                                notificacion('No Enviado', 'waring');
                                bootbox.hideAll();

                                //   window.location.href = "guiaventas/default/imprimir-factura/" +response ;


                            } else if (response == 3) {
                                notificacion('Documento ya informado a sunat', 'error');
                                bootbox.hideAll();

                                //   window.location.href = "guiaventas/default/imprimir-factura/" +response ;


                            } else if (response == 0) {
                                notificacion('Error', 'error');
                                bootbox.hideAll();

                                //   window.location.href = "guiaventas/default/imprimir-factura/" +response ;


                            }
                        }


                    });
                }
            });
        });

    }, 'json');
} 