function funcionEditar(id) {
    $.post(APP_URL + '/estados/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Estado</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-estados").validate({
                    rules: {
                        nombre_estado: "required",
                         tipo_estado: "required",
                        siglas: "required",
                    },
                    messages: {

                        nombre_estado: "Por favor ingrese datos",
                        tipo_estado: "Por favor ingrese datos",
                        siglas: "Por favor ingrese datos",

                    },
                    submitHandler: function () {
                        var nombre_estado = $("#nombre_estado").val();
                        var tipo_estado = $("#tipo_estado").val();
                        var siglas = $("#siglas").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/estados/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_estado: id,
                                nombre_estado: nombre_estado,
                                tipo_estado: tipo_estado,
                                siglas: siglas,
                                
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
