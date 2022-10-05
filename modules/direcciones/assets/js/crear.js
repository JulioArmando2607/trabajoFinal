$("#modal-direcciones").on("click", function () {
    $.post(APP_URL + '/direcciones/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Direcciones</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#entidad").select2({
            placeholder: "Seleccioné Entidad"
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-direcciones").validate({
                    rules: {
                        entidad: "required",
                        ubigeos: "required",
                        direccion: "required",
                        urbanizacion: "required",
                        referencias: "required",

                    },
                    messages: {
                        entidad: "Por favor ingrese datos",
                        ubigeos: "Por favor ingrese datos",
                        direccion: "Por favor ingrese datos",
                        urbanizacion: "Por favor ingrese datos",
                        referencias: "Por favor ingrese datos",

                    },
                    submitHandler: function () {
                        var entidad = $("#entidad").val();
                        var ubigeos = $("#ubigeos").val();
                        var direccion = $("#direccion").val();
                        var urbanizacion = $("#urbanizacion").val();
                        var referencias = $("#referencias").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/direcciones/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                entidad: entidad,
                                ubigeos: ubigeos,
                                direccion: direccion,
                                urbanizacion: urbanizacion,
                                referencias: referencias
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
