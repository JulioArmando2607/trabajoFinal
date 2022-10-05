$("#modal-vehiculosn").on("click", function () {
    $.post(APP_URL + '/vehiculosn/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro vehiculo</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardarv").click(function () {
                $("#form-vehiculosn").validate({
                    rules: {
                        marca: "required",
                        version: "required",
                        modelo: "required",
                        matricula: "required",
                        denominacion_comercial: "required",
                        medida_neumatico: "required",
                        altura: "required",
                        anchura: "required",
                        longitud: "required",
                        tipo_motor: "required",
                        numero_cilindros: "required",
                        potencia_expresada_en_cv: "required",
                        potencia_expresada_en_kw: "required",
                        numero_bastidor: "required",
                        numero_plazas: "required",
                        descripcion: "required",
                        incripcion: "required",
                        config_vehicular: "required",

                    },
                    messages: {

                        marca: "Por favor ingrese datos",
                        version: "Por favor ingrese datos",
                        modelo: "Por favor ingrese datos",
                        matricula: "Por favor ingrese datos",
                        denominacion_comercial: "Por favor ingrese datos",
                        medida_neumatico: "Por favor ingrese datos",
                        altura: "Por favor ingrese datos",
                        anchura: "Por favor ingrese datos",
                        longitud: "Por favor ingrese datos",
                        tipo_motor: "Por favor ingrese datos",
                        numero_cilindros: "Por favor ingrese datos",
                        potencia_expresada_en_cv: "Por favor ingrese datos",
                        potencia_expresada_en_kw: "Por favor ingrese datos",
                        numero_bastidor: "Por favor ingrese datos",
                        numero_plazas: "Por favor ingrese datos",
                        descripcion: "Por favor ingrese datos",
                        incripcion: "Por favor ingrese datos",
                        config_vehicular: "Por favor ingrese datos"
                    },
                    submitHandler: function () {

                       var marca = $("#marca").val();
                       var version = $("#version").val();
                       var modelo = $("#modelo").val();
                       var matricula = $("#matricula").val();
                       var denominacion_comercial = $("#denominacion_comercial").val();
                       var medida_neumatico = $("#medida_neumatico").val();
                       var altura = $("#altura").val();
                       var anchura = $("#anchura").val();
                       var longitud = $("#longitud").val();
                       var tipo_motor = $("#tipo_motor").val();
                       var numero_cilindros = $("#numero_cilindros").val();
                       var potencia_expresada_en_cv = $("#potencia_expresada_en_cv").val();
                       var potencia_expresada_en_kw = $("#potencia_expresada_en_kw").val();
                       var numero_bastidor = $("#numero_bastidor").val();
                       var numero_plazas = $("#numero_plazas").val();
                       var descripcion = $("#descripcion").val();
                       var incripcion = $("#incripcion").val();
                       var config_vehicular = $("#config_vehicular").val();
                       var tara = $("#tara").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/vehiculosn/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                marca: marca,
                                version: version,
                                modelo: modelo,
                                matricula: matricula,
                                denominacion_comercial: denominacion_comercial,
                                medida_neumatico: medida_neumatico,
                                altura: altura,
                                anchura: anchura,
                                longitud: longitud,
                                tipo_motor: tipo_motor,
                                numero_cilindros: numero_cilindros,
                                potencia_expresada_en_cv: potencia_expresada_en_cv,
                                potencia_expresada_en_kw: potencia_expresada_en_kw,
                                numero_bastidor: numero_bastidor,
                                numero_plazas: numero_plazas,
                                descripcion: descripcion,
                                incripcion: incripcion,
                                tara: tara,
                                config_vehicular:config_vehicular
                                 
                            },
                            success: function (response) {

                                if (response) {
                                    console.log(response);
                                    notificacion('Accion realizada con exito', 'success');
                                    bootbox.hideAll();
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
