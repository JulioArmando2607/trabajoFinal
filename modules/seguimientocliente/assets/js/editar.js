
$(document).ready(function () {

    mostrarTablaTemporalRC();

    $("#btn-guardar").click(function () {
        $("#frm-seguimiento").validate({
            rules: {
                estado: "required",
                comentario: "required",
            },
            messages: {
                estado: "Ingrese datos",
                comentario: "Ingrese datos",
            },
            submitHandler: function () {
                var estado = $("#estado").val();
                var comentario = $("#comentario").val();

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
                        window.location.href = APP_URL + '/seguimiento';
                    }
                });
            }
        });
    });
});


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
                    temporalDetalleGuia += '<tr><td>' + item.gr + '</td><td>' + item.estado_mercaderia + '</td><td>' + item.fecha_hora_entrega + '</td><td>' + item.hora_entrega + '</td><td>' + item.estado_cargo + '</td><td>' + item.fecha_cargo + '</td><td>' + item.recibido_por + '</td><td>' + item.obsevacion + '</td><td>' + item.fecha_act + '</td><td><i class="flaticon-edit text-danger bg-hover-light" onclick="funcionEditarGC(' + item.id_guia_remision_cliente + ')"></i></td></tr>';
                });

                $("#tabla-detalle-guia-rc").html(temporalDetalleGuia);
            }
        });
    }
}

 $(document).ready(function () {
            $("#btn-guardar").on('click', function () {
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
                        }  
                    }
                });

            });
        });
$(document).ready(function () {
    $("#btn-guardar").on('click', function () {
        var formData = new FormData();
        var files = $('#image')[0].files[0];
        var id_guia_remision = $("#id_guia_remision").val();
        var id_archivo = $("#id_archivo").val();
        formData.append('file', files);
        $.ajax({
            url: APP_URL + '/seguimiento/default/uload/' + id_guia_remision +"?id_archivo=" + id_archivo,
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

