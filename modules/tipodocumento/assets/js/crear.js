$("#modal-tipodocumento").on("click", function () {
    $.post(APP_URL + '/tipodocumento/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Tipo Estado</strong></h2>",
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
                            url: APP_URL + '/tipodocumento/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

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
});
