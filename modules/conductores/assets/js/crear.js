$("#modal-conductores").on("click", function () {
    $.post(APP_URL + '/conductores/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Conductor</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#personas").select2({
            placeholder: "Seleccioné Persona"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-conductores").validate({
                    rules: {
                     
                        personas: "required",
                        conductor: "required",
                        licencia:"required"
                        
                    },
                    messages: {                    
                      
                        personas: "Por favor seleccione",
                        conductor: "Por favor seleccione",
                        licencia: "Por favor seleccione"

                    },
                    submitHandler: function () {

                        var personas = $("#personas").val();
                        var conductor = $("#conductor").val();
                         var licencia = $("#licencia").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/conductores/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                personas: personas,
                                conductor: conductor,
                                licencia:licencia
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
