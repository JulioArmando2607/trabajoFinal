
function funcionEditarGC(id) {

    $.post(APP_URL + '/seguimiento/default/get-modal-edit-g-c/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro de seguimiento</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardarc").click(function () {





                $("#form-editargcliente").validate({
                    rules: {

                    },
                    messages: {

                    },
                    submitHandler: function () {

                        var estado_mercaderia = $("#estado_mercaderia").val();
                        var estado_cargo = $("#estado_cargo").val();
                        var recibido_por = $("#recibido_por").val();
                        var obsevacion = $("#obsevacion").val();
                        var fecha_hora_entrega = $("#fecha_hora_entrega").val();
                        var fecha_cargo = $("#fecha_cargo").val();
                        var hora_entrega = $("#hora_entrega").val();
                        var entregado_por = $("#entregado_por").val();
                       var checkaplicartodos=  $("#checkaplicartodos").val();
                        var id_guia_remision=  $("#id_guia_remision").val();
                        var selected = [];
                        $(":checkbox[name=checkaplicartodos]").each(function () {
                            if (this.checked) {
                                // agregas cada elemento.
                                selected.push($(this).val());
                            }
                        });
                  //      console.log(JSON.stringify($('[name=checkaplicartodos]').serializeArray()));
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/seguimiento/default/updateg',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_guia_remision_cliente: id,
                                estado_mercaderia: estado_mercaderia,
                                estado_cargo: estado_cargo,
                                recibido_por: recibido_por,
                                entregado_por: entregado_por,
                                obsevacion: obsevacion,
                                fecha_hora_entrega: fecha_hora_entrega,
                                fecha_cargo: fecha_cargo,
                                hora_entrega: hora_entrega,
                                id_guia_remision:id_guia_remision,
                                chekAplicarTodos:JSON.stringify($('[name=checkaplicartodos]').serializeArray())

                            },
                            success: function (response) {
                              //  alert(response);


                                if (response) {

                                    mostrarTablaTemporalRC();
                                    bootbox.hideAll();
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }

                            }
                        });
                    }
                });
            });
        });



        $(document).ready(function () {
            $("#btn-guardarc").on('click', function () {
                var formData = new FormData();
                var files = $('#image')[0].files[0];
                var id_guia_remision_cliente = $("#id_guia_remision_cliente").val();
                var id_archivo = $("#id_archivo_cli").val();
                formData.append('file', files);
                $.ajax({
                    url: APP_URL + '/seguimiento/default/uloadrc/' + id_guia_remision_cliente,
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {

                    }
                });

            });
        });

        $("#image").change(function () {

            filePreview(this);

        });

        function filePreview(input) {

            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.readAsDataURL(input.files[0]);

                reader.onload = function (e) {

                    $("#segcl").attr("src", e.target.result);

                }

            }

        }

    }, 'json');
}




 