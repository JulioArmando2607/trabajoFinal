function funcionEditar(id) {
    $.post(APP_URL + '/productos/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Producto</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-productos").validate({
                    rules: {
                        dni: {
                            required: true,
                            number: true,
                            minlength: 8,
                            maxlength: 8,
                        },
                         
                        cod_producto: "required",
                        nombre_producto: "required",
                        unidad_medida: "required",
                     
                    },
                    messages: {
                     
                        cod_producto: "Por favor ingrese datos",
                        unidad_medida: "Por favor ingrese datos",
                        nombre_producto: "Por favor ingrese datos",
                  
                    },
                    submitHandler: function () {
                        var cod_producto = $("#cod_producto").val();
                        var unidad_medida = $("#unidad_medida").val();
                        var nombre_producto = $("#nombre_producto").val();
                       

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/productos/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_producto: id,
                                cod_producto: cod_producto,
                                unidad_medida: unidad_medida,
                                nombre_producto: nombre_producto,
                               
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
