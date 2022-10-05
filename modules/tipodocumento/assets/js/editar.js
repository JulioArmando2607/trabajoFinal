function funcionEditar(id) {
    $.post(APP_URL + '/tipodocumento/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Tipo Documento</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-tipodocumento").validate({
                    rules: {

                        documento: "required",

                    },
                    messages: {

                        documento: "Por favor ingrese datos",

                    },
                    submitHandler: function () {

                        var documento = $("#documento").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tipodocumento/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_tipo_documento: id,

                                documento: documento,

                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
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
