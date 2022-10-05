function funcionEditar(id) {
    $.post(APP_URL + '/marcavehiculo/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Marca Vehiculo</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-marcavehiculo").validate({
                    rules: {
     
                      
                        nombre_marca: "required",
                      
                    },
                    messages: {
                    
                        nombre_marca: "Por favor ingrese datos",
                    
                    },
                    submitHandler: function () {
                     
                        var nombre_marca = $("#nombre_marca").val();
                        

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/marcavehiculo/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_marca: id,
                             
                                nombre_marca: nombre_marca,
                               
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
