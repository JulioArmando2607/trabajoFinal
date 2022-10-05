$("#modal-productos").on("click", function () {
    $.post(APP_URL + '/productos/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Productos</strong></h2>",
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
                       
                        cod_producto: "required",
                        nombre_producto: "required",
                        unidad_medida: "required",
                     
                    },
                    messages: {
                      
                        cod_producto: "Por favor ingrese datos",
                        nombre_producto: "Por favor ingrese datos",
                        unidad_medida: "Por favor ingrese datos",
                      
                    },
                    submitHandler: function () {
                        var cod_producto = $("#cod_producto").val();
                        var nombre_producto = $("#nombre_producto").val();
                        var unidad_medida = $("#unidad_medida").val();
                      
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/productos/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                cod_producto: cod_producto,
                                nombre_producto: nombre_producto,
                                unidad_medida: unidad_medida,
 
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
