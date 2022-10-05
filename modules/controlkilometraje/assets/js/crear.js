$("#modal-Control-Kilometraje").on("click", function () {
    $.post(APP_URL + '/controlkilometraje/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro CK</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
       $("#distrito").select2({
            placeholder: "Seleccioné"
        });
        $("#kilometraje_llegada").keyup(function () {
            var kilometraje_salida = $("#kilometraje_salida").val();
            var kilometraje_llegada = $("#kilometraje_llegada").val();
            var resultar=kilometraje_llegada-kilometraje_salida;
            $("#kilometro_recorrido").val(resultar);
        });
        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-control-kilometraje").validate({
                    rules: {
                        marca_vehiculo: "required",
                        placa: "required",
                        descripcion: "required",
                        incripcion: "required"

                    },
                    messages: {
                        marca_vehiculo: "Por favor ingrese datos",
                        placa: "Por favor ingrese datos",
                        descripcion: "Por favor ingrese datos",
                        incripcion: "Por favor ingrese datos",
                        

                    },
                    submitHandler: function () {
                        var vehiculo = $("#vehiculo").val();
                        var hora_salida = $("#hora_salida").val();
                        var hora_llegada = $("#hora_llegada").val();
                        var kilometraje_salida = $("#kilometraje_salida").val();
                        var kilometraje_llegada = $("#kilometraje_llegada").val();
                        var kilometro_recorrido = $("#kilometro_recorrido").val();
                        var distrito = $("#distrito").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/controlkilometraje/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                vehiculo: vehiculo,
                                hora_salida: hora_salida,
                                hora_llegada: hora_llegada,
                                kilometraje_salida: kilometraje_salida,
                                kilometraje_llegada:kilometraje_llegada,
                                kilometro_recorrido:kilometro_recorrido,
                                distrito:distrito,

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
