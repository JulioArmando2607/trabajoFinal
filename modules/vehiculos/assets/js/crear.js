$("#modal-vehiculos").on("click", function () {
    $.post(APP_URL + '/vehiculos/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Vehiculos</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
 

        $("#marca_vehiculo").select2({
            placeholder: "SeleccionÃ© Marca"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-vehiculos").validate({
                    rules: {
                        marca_vehiculo: "required",
                        placa: "required",
                        descripcion: "required",
                        incripcion: "required",
              

                    },
                    messages: {
                        marca_vehiculo: "Por favor ingrese datos",
                        placa: "Por favor ingrese datos",
                        descripcion: "Por favor ingrese datos",
                        incripcion: "Por favor ingrese datos",
                        

                    },
                    submitHandler: function () {
                        var marca_vehiculo = $("#marca_vehiculo").val();
                        var placa = $("#placa").val();
                        var descripcion = $("#descripcion").val();
                        var incripcion = $("#incripcion").val();
                       var config_vehicular = $("#config_vehicular").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/vehiculos/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                marca_vehiculo: marca_vehiculo,
                                placa: placa,
                                descripcion: descripcion,
                                incripcion: incripcion,
                                config_vehicular:config_vehicular
                                 
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
