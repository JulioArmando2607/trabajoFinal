function funcionEditarCaja(id) {
    $.post(APP_URL + '/cajas/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Caja</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#cajero").select2({
            placeholder: "Seleccione Cajero"
        })

        $(document).ready(function () {
            $("#btn-actualizar").click(function () {
                $("#form-caja").validate({
                    rules: {
                        codigo: "required",
                        cajero: "required",
                        detalle: "required",
                    },
                    messages: {
                        codigo: "Por favor ingrese dato",
                        cajero: "Por favor ingrese dato",
                        detalle: "Por favor ingrese dato"
                    },
                    submitHandler: function () {
                        var codigo = $("#codigo").val();
                        var cajero = $("#cajero").val();
                        var detalle = $("#detalle").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/cajas/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_caja: id,
                                codigo: codigo,
                                cajero: cajero,
                                detalle: detalle
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatableCaja.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}
