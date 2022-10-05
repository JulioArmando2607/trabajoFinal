function funcionEditar(id) {
    $.post(APP_URL + '/agente/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Agente</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-agente").validate({
                    rules: {
                      
                        cuenta: "required",
                        agente: "required",
                      
                    },
                    messages: {
                   
                        cuenta: "Por favor ingrese datos",
                        agente: "Por favor ingrese datos",
                    
                    },
                    submitHandler: function () {
                        var cuenta = $("#cuenta").val();
                        var agente = $("#agente").val();
                        

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/agente/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_agente: id,
                            
                                cuenta: cuenta,
                                agente: agente,
                               
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
