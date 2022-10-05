$("#modal-via").on("click", function () {
    $.post(APP_URL + '/via/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Agente</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-via").validate({
                    rules: {

                        nombre_via: "required",

                    },
                    messages: {

                        nombre_via: "Por favor ingrese datos",

                    },
                    submitHandler: function () {

                        var nombre_via = $("#nombre_via").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/via/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                nombre_via: nombre_via,

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
