function funcionEditar(id) {
    $.post(APP_URL + '/direcciones/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Entidad</strong></h2>",
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
                 
                    },
                    messages: {

                        entidad: "Por favor ingrese datos",
                        ubigeos: "Por favor ingrese datos",
                        direccion: "Por favor ingrese datos",
                  

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
                            url: APP_URL + '/direcciones/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_direccion: id,
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
}
