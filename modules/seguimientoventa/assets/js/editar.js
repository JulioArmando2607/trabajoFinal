

$(document).ready(function () {


});

/*
 $("#btn-guardar-gvs").click(function () {
 
 $("#frm-seguimiento-gv").validate({
 rules: {
 estado: "required",
 
 },
 messages: {
 estado: "Ingrese datos",
 
 },
 submitHandler: function () {
 var formData = new FormData();
 var estado = $("#estado").val();
 var comentario = $("#comentario").val();
 var id_estado_venta = $("#id_estado_venta").val();
 var factura_boleta = $("#factura_boleta").val();
 var fecha_entrega = $("#fecha_entrega").val();
 var file = $('#image')[0].files[0];
 var id_guia_venta = $("#id_guia_venta").val();
 
 formData.append('estado', estado);
 formData.append('comentario', comentario);
 formData.append('id_estado_venta', id_estado_venta);
 formData.append('factura_boleta', factura_boleta);
 formData.append('fecha_entrega', fecha_entrega);
 formData.append('file', file);
 formData.append('id_guia_venta', id_guia_venta);
 
 $.ajax({
 type: "POST",
 dataType: 'json',
 url: APP_URL + '/seguimientoventa/default/update',
 contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
 data: formData,
 //  data: formData,
 contentType: false,
 processData: false,
 success: function (response) {
 window.location.href = APP_URL + '/seguimientoventa';
 }
 });
 }
 });
 });
 */
$(document).ready(function () {


    $("#btn-guardar-gvs").click(function () {

        $("#frm-seguimiento-gv").validate({
            rules: {
                estado: "required",

            },
            messages: {
                estado: "Ingrese datos",

            },
            submitHandler: function () {

                var estado = $("#estado").val();
                var comentario = $("#comentario").val();
                var id_estado_venta = $("#id_estado_venta").val();
                var factura_boleta = $("#factura_boleta").val();
                var fecha_entrega = $("#fecha_entrega").val();
               // addFoto();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/seguimientoventa/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_guia_venta: $("#id_guia_venta").val(),
                        estado: estado,
                        fecha_entrega: fecha_entrega,
                        comentario: comentario,
                        id_estado_venta: id_estado_venta,
                        factura_boleta: factura_boleta,

                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/seguimientoventa';
                    }
                });
            }
        });
    });
});

$("#image").change(function () {
    var formData = new FormData();
    var file = $('#image')[0].files[0];
    var id_guia_venta = $("#id_guia_venta").val();

    formData.append('file', file);
    formData.append('id_guia_venta', id_guia_venta);

    $.ajax({
        url: APP_URL + '/seguimientoventa/default/uload/' + id_guia_venta,
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
})
/*
function addFoto() {
    var formData = new FormData();
    var file = $('#image')[0].files[0];
    var id_guia_venta = $("#id_guia_venta").val();

    formData.append('file', file);
    formData.append('id_guia_venta', id_guia_venta);
    $.ajax({
        url: APP_URL + '/seguimientoventa/default/uload/' + id_guia_venta,
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {

        }
    });
}*/

/*$(document).ready(function () {
 $("#btn-guardar-gvs").on('click', function (e) {
 e.preventDefault();
 var formData = new FormData();
 var estado = $("#estado").val();
 var comentario = $("#comentario").val();
 var id_estado_venta = $("#id_estado_venta").val();
 var factura_boleta = $("#factura_boleta").val();
 var fecha_entrega = $("#fecha_entrega").val();
 var file = $('#image')[0].files[0];
 var id_guia_venta = $("#id_guia_venta").val();
 
 formData.append('estado', estado);
 formData.append('comentario', comentario);
 formData.append('id_estado_venta', id_estado_venta);
 formData.append('factura_boleta', factura_boleta);
 formData.append('fecha_entrega', fecha_entrega);
 formData.append('file', file);
 formData.append('id_guia_venta', id_guia_venta);
 $.ajax({
 url: APP_URL + '/seguimientoventa/default/uload/' + id_guia_venta,
 type: 'post',
 data: formData,
 contentType: false,
 processData: false,
 success: function (response) {
 
 }
 });
 
 });
 });
 */


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



/*function guardarImagen () {
 var formData = new FormData();
 var files = $('#image')[0].files[0];
 formData.append('file', files);
 $.ajax({
 url: APP_URL + '/seguimientoventa/default/uload',
 type: 'post',
 data: formData,
 contentType: false,
 processData: false,
 success: function (response) {
 if (response != 0) {
 $(".card-img-top").attr("src", response);
 } else {
 alert('Formato de imagen incorrecto.');
 } 
 }
 });
 return false;
 }*/
/*
 $(document).ready(function () {
 $("#btn-guardar-gvs").on('click', function () {
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
 
 }
 });
 
 });
 }); */