function funcionEditar(id) {
    $.post(APP_URL + '/productosinventario/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Persona</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-producto").validate({
                    rules: {

                        persona: "required",

                    },
                    messages: {

                        persona: "Por favor seleccione",
                    },
                    submitHandler: function () {
                        var nombre = $("#nombre").val();
                        var precio = $("#precio").val();
                        var cantidad = $("#cantidad").val();
                        var medida = $("#medida").val();
                        var descripcion = $("#descripcion").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/productosinventario/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_producto: id,
                                nombre: nombre,
                                precio: precio,
                                cantidad: cantidad,
                                medida: medida,
                                descripcion: descripcion,

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
