function funcionEditar(id) {
    $.post(APP_URL + '/vehiculosn/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Vehiculo</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

    
        $("#marca_vehiculo").select2({
            placeholder: "Seleccioné Marca"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-vehiculosn").validate({
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
                            url: APP_URL + '/vehiculosn/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_vehiculo: id,
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
                                    bootbox.hideAll();
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
