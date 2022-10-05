$("#modal-persona").on("click", function () {
    $.post(APP_URL + '/persona/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Persona</strong></h2>",
            message: resp.plantilla,
              size: 'large',
            buttons: {}
        });

  $("#divlicencia").hide();
    

    $("#conductor").change(function () {
        if (($(this).val()) == 1) {
            $("#divlicencia").show();
            
        } else {

            $("#divlicencia").hide();
          
        }


    });
        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#entidad").select2({
            placeholder: "Seleccioné Entidad"
        });

        $("#dni").blur(function () {
          var dni = $("#dni").val();
            //   var serie = $("#serie").val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/persona/default/buscar-numero-doc',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    numero: dni,

                },
                success: function (response) {
                    if (response.total > 0) {
                        notificacion('El número de Documento ' + dni + ' ya esta registrado.', 'warning');
                        $("#dni").val("")
                    }
                }
            });
        });
        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-persona").validate({
                    rules: {
                        dni: {
                            required: true,
                            number: true,
                            minlength: 8,
                            maxlength: 8
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
                        var entidad = $("#entidad").val();
                        var sexo = $("#sexo").val();
                        //var empleado = $("#empleado").val();
                        var conductor = $("#conductor").val();
                        var licencia = $("#licencia").val();
                        var telefono =  $("#telefono").val();
                        var correo= $("#correo").val();
                        var fecha_nacimiento= $("#fecha_nacimiento").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/persona/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                dni: dni,
                                nombres: nombres,
                                apellido_paterno: apellido_paterno,
                                apellido_materno: apellido_materno,
                                entidad: entidad,
                                sexo: sexo,
                               // empleado: empleado,
                                conductor: conductor,
                                licencia:licencia,
                                telefono:telefono,
                                correo:correo,
                                fecha_nacimiento:fecha_nacimiento
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
