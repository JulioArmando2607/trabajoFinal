
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
                                hora_entrega: hora_entrega

                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    mostrarTablaTemporalRC();
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
                formData.append('file', files);
                $.ajax({
                    url: APP_URL + '/seguimientoventa/default/subir',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response != 0) {

                            $(".card-img-top").attr("src", response);
                        } else {
                            alert('No ingreso imagen' + response);
                        }
                    }
                });

            });
        });

        $(document).ready(function () {
            $("#btn-guardarc").on('click', function () {
                var formData = new FormData();
                var files = $('#image')[0].files[0];
                var id_guia_remision_cliente = $("#id_guia_remision_cliente").val();
                var id_archivo = $("#id_archivo").val();
                formData.append('file', files);
                $.ajax({
                    url: APP_URL + '/seguimiento/default/uloadrc/' + id_guia_remision_cliente + "?id_archivo=" + id_archivo,
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        /*  if (response != 0) {
                         
                         $(".card-img-top").attr("src", response);
                         } else {
                         alert('No ingreso imagen' + response);
                         }*/
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

                    //    $('#uploadForm + img').remove();
                    $(".card-img-top").attr("src", e.target.result);
                    //    $('#uploadForm').after('<img src="' + e.target.result + '" width="450" height="300"/>');
                    //<img class="card-img-top" src="<?= $seguimiento["nombre_ruta"] ?>">
                }

            }

        }

    }, 'json');
}




 