function funcionFacturacion(id) {
    $.post(APP_URL + '/guiaventas/default/get-modal-facturacion/' + id, {}, function (resp) {
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


        $("#btn-factura-crear").click(function () {
            $("#frm-guia-venta-facturacion").validate({

                submitHandler: function () {


                    var id_guia_venta = $("#id_guia_venta").val();
                    $.showLoading();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/guiaventas/default/factura',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                            id_guia_venta: id_guia_venta

                        },

                        success: function (response) {
                               $.hideLoading();
                            if (response) {
                              
                                alert('vamos'+response)
                                datatableGuia.reload();
                                bootbox.hideAll();
                                window.open('guiaventas/default/imprimir-factura/'+response, '_blank');
                               /// window.location.href = "guiaventas/default/imprimir-factura/" +response ;
                            } else if (response) {

                            }
                        }
                        

                    });
                }
            });
        });

    }, 'json');
}



 