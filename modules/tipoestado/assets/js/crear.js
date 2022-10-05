$("#modal-tipoestado").on("click", function () {
    $.post(APP_URL + '/tipoestado/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Tipo Estado</strong></h2>",
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
                            url: APP_URL + '/tipoestado/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                               
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
});
