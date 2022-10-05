function funcionEditar(id) {
    $.post(APP_URL + '/conductores/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Persona</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        
         $("#personas").select2({
            placeholder: "Seleccioné Persona"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-conductores").validate({
                    rules: {
                        dni: {
                            required: true,
                            number: true,
                            minlength: 8,
                            maxlength: 8,
                        },
                        nombres: "required",
                        apellido_paterno: "required",
                        apellido_materno: "required",
                        sexo: "required",
                    },
                    messages: {
                        dni: {
                            required: "Por favor ingrese DNI",
                            number: "Por favor ingrese un número valido.",
                            minlength: "DNI debe contener 8 digitos.",
                            maxlength: "DNI debe contener 8 digitos.",
                        },
                        nombres: "Por favor ingrese datos",
                        apellido_paterno: "Por favor ingrese datos",
                        apellido_materno: "Por favor ingrese datos",
                        sexo: "Por favor seleccione",
                    },
                    submitHandler: function () {
                        var dni = $("#dni").val();
                        var nombres = $("#nombres").val();
                        var apellido_paterno = $("#apellido_paterno").val();
                        var apellido_materno = $("#apellido_materno").val();
                        var sexo = $("#sexo").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/conductores/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_empleado: id,
                                dni: dni,
                                nombres: nombres,
                                apellido_paterno: apellido_paterno,
                                apellido_materno: apellido_materno,
                                sexo: sexo
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
