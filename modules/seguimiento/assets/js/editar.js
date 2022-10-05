/**
 *Funcion para activar el loader antes de cargar la vista
 */
window.onbeforeunload = function () {
    $.showLoading();
}

/**
 * Funcion para validar la carga de pagina para el loader
 */
window.onload = function () {
    $.hideLoading();
}


$(document).ready(function () {

    mostrarTablaTemporalRC();

    $("#btn-guardar").click(function () {

        $("#estado").change(function () {});


        if( $("#estado").val()==4){


            if( $("#image_seg").val()==''){
                alert( 'Ingresar Imagen');

            } else{

                $("#frm-seguimiento").validate({
                    rules: {
                        estado: "required",

                    },
                    messages: {
                        estado: "Ingrese datos",

                    },
                    submitHandler: function () {
                        var estado = $("#estado").val();
                        var comentario = $("#comentario").val();

                        $.showLoading();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/seguimiento/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_guia_remision: $("#id_guia_remision").val(),
                                estado: estado,
                                comentario: comentario,

                            },
                            success: function (response) {
                                $.hideLoading();
                                //  alert(response);
                                window.location.href = APP_URL + '/seguimiento';

                                $.ajax({
                                    type: "POST",
                                    dataType: 'json',
                                    url: APP_URL + '/seguimiento/default/estado-guia',
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    data: {
                                        pedido: response,


                                    },
                                    success: function (response) {
                                        window.location.href = APP_URL + '/seguimiento';
                                    }
                                });
                                //   agregarImg()
                                //
                            }
                        });
                    }
                });
            }

        } else {
            $("#frm-seguimiento").validate({
                rules: {
                    estado: "required",

                },
                messages: {
                    estado: "Ingrese datos",

                },
                submitHandler: function () {
                    var estado = $("#estado").val();
                    var comentario = $("#comentario").val();

                    $.showLoading();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/seguimiento/default/update',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                            id_guia_remision: $("#id_guia_remision").val(),
                            estado: estado,
                            comentario: comentario,

                        },
                        success: function (response) {
                            $.hideLoading();
                            //  alert(response);
                            window.location.href = APP_URL + '/seguimiento';

                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/seguimiento/default/estado-guia',
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                data: {
                                    pedido: response,


                                },
                                success: function (response) {
                                    window.location.href = APP_URL + '/seguimiento';
                                }
                            });
                            //   agregarImg()
                            //
                        }
                    });
                }
            });
        }

    /*    $("#frm-seguimiento").validate({
            rules: {
                estado: "required",

            },
            messages: {
                estado: "Ingrese datos",

            },
            submitHandler: function () {
                var estado = $("#estado").val();
                var comentario = $("#comentario").val();

                $.showLoading();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/seguimiento/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_guia_remision: $("#id_guia_remision").val(),
                        estado: estado,
                        comentario: comentario,

                    },
                    success: function (response) {
                        $.hideLoading();
                      //  alert(response);
                       window.location.href = APP_URL + '/seguimiento';

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/seguimiento/default/estado-guia',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                pedido: response,


                            },
                            success: function (response) {

                            }
                        });
                        //   agregarImg()
                        //window.location.href = APP_URL + '/seguimiento';
                    }
                });
            }
        });*/
    });
});

$("#estado").change(function () {});


console.log('aquiii->' +$("#estado").val())
    if($("#estado").val()==4){

    }

$("#image_seg").change(function () {

    var formData = new FormData();
    var files = $('#image_seg')[0].files[0];
    var id_guia_remision = $("#id_guia_remision").val();
    var id = $("#id_archivo").val();

    formData.append('file', files);
    if (id) {
        formData.append('idArchivo', id);
    } else {
        formData.append('idArchivo', null);
    }
    formData.append('idGuia', id_guia_remision);

    $.ajax({
        url: APP_URL + '/seguimiento/default/cargar-imagen',
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response > 0) {
                notificacion('Se subio archivo al servidor', 'success');
            } else {
                notificacion('Error no se puedo subir archivo, intente nuevamente', 'error');
            }
        }
    });
});

/*$("#image_seg").change(function () {

    var formData = new FormData();
    var files = $('#image_seg')[0].files[0];
    var id_guia_remision = $("#id_guia_remision").val();
    var id = $("#id_archivo").val();

    formData.append('file', files);
    if (id) {
        formData.append('idArchivo', id);
    } else {
        formData.append('idArchivo', null);
    }
    formData.append('idGuia', id_guia_remision);

    $.ajax({
        url: APP_URL + '/seguimiento/default/cargar-imagen',
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response > 0) {
                notificacion('Se subio archivo al servidor', 'success');
            } else {
                notificacion('Error no se puedo subir archivo, intente nuevamente', 'error');
            }
        }
    });
})*/

function mostrarTablaTemporalRC() {
    if ($("#id_guia_remision").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/seguimiento/default/guia-cliente',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_guia_remision: $("#id_guia_remision").val(),
            },
            success: function (response) {

                let temporalDetalleGuia = "";

                response.forEach(item => {
                    temporalDetalleGuia += '<tr><td>' + item.gr + '</td><td>' + item.estado_mercaderia + '</td><td>' + item.fecha_hora_entrega + '</td><td>' + item.hora_entrega + '</td><td>' + item.estado_cargo + '</td><td>' + item.fecha_cargo + '</td><td>' + item.recibido_por + '</td><td>' + item.observacion + '</td><td>' + item.fecha_act + '</td><td><i class="flaticon-edit text-danger bg-hover-light" onclick="funcionEditarGC(' + item.id_guia_remision_cliente + ')"></i></td></tr>';
                });

                $("#tabla-detalle-guia-rc").html(temporalDetalleGuia);
            }
        });
    }
}

$("#image_seg").change(function () {

    filePreview(this);

});

function filePreview(input) {

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.readAsDataURL(input.files[0]);

        reader.onload = function (e) {

            //    $('#uploadForm + img').remove();
            $("#segpeg").attr("src", e.target.result);
            //    $('#uploadForm').after('<img src="' + e.target.result + '" width="450" height="300"/>');
            //<img class="card-img-top" src="<?= $seguimiento["nombre_ruta"] ?>">
        }

    }

}