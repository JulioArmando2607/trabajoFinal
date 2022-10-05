function funcionEditar(id) {
    $.post(APP_URL + '/persona/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Persona</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#entidad").select2({
            placeholder: "Seleccioné Entidad"
        });

        $("#divlicencia").hide();


        if (($("#conductor").val()) == 1) {
            $("#divlicencia").show();

        } else {

            $("#divlicencia").hide();

        }

        $("#conductor").change(function () {
            if (($(this).val()) == 1) {
                $("#divlicencia").show();

            } else {

                $("#divlicencia").hide();

            }


        });


        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-persona").validate({
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
                        var entidad = $("#entidad").val();


                        //var empleado = $("#empleado").val();
                        var conductor = $("#conductor").val();
                        var licencia = $("#licencia").val();
                        var telefono = $("#telefono").val();
                        var correo = $("#correo").val();
                        var fecha_nacimiento = $("#fecha_nacimiento").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/persona/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_persona: id,
                                dni: dni,
                                nombres: nombres,
                                apellido_paterno: apellido_paterno,
                                apellido_materno: apellido_materno,
                                sexo: sexo,
                                entidad: entidad,
                                conductor: conductor,
                                licencia: licencia,
                                telefono: telefono,
                                correo: correo,
                                fecha_nacimiento: fecha_nacimiento,

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
