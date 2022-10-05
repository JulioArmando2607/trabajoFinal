function funcionEditar(id) {
    $.post(APP_URL + '/controlkilometraje/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Control Kilometraje</strong></h2>",
            message: resp.plantilla,
            buttons: {},
            size:'large'

        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

    
        $("#marca_vehiculo").select2({
            placeholder: "Seleccioné Marca"
        });
        $("#distrito").select2({
            placeholder: "Seleccioné"
        });

        $(document).ready(function () {
            $("#btn-actualizar").click(function () {
                $("#form-control-kilometraje").validate({
                    rules: {
                        marca_vehiculo: "required",
                        placa: "required",
                        descripcion: "required",
                        inscripcion: "required",

                    },
                    messages: {
                        marca_vehiculo: "Por favor ingrese datos",
                        placa: "Por favor ingrese datos",
                        descripcion: "Por favor ingrese datos",
                        inscripcion: "Por favor ingrese datos",

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
                            url: APP_URL + '/controlkilometraje/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id:id,
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
}
