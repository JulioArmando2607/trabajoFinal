function funcionEditar(id) {
    $.post(APP_URL + '/tipoestado/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Tipo Estado</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-tipoestado").validate({
                    rules: {
                      
                      /*siglas
nombre_tipo
*/
                        siglas: "required",
                        nombre_tipo: "required",
                      
                    },
                    messages: {
                   
                        siglas: "Por favor ingrese datos",
                        nombre_tipo: "Por favor ingrese datos",
                    
                    },
                    submitHandler: function () {
                        var siglas = $("#siglas").val();
                        var nombre_tipo = $("#nombre_tipo").val();
                        

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tipoestado/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_tipo_estado: id,
                            
                                siglas: siglas,
                                nombre_tipo: nombre_tipo,
                               
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
