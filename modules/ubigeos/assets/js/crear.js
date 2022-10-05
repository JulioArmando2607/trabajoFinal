$("#modal-ubigeos").on("click", function () {
    $.post(APP_URL + '/ubigeos/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Ubigeos</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-ubigeos").validate({
                    rules: {

                        cuenta: "required",
                        ubigeos: "required",
                        ubdep: "required",
                        departamento: "required",
                        ubprov: "required",
                        provincia: "required",
                        ubdistr: "required",
                        distrito: "required"
                    },
                    messages: {

                        cuenta: "Por favor ingrese datos",
                        ubigeos: "Por favor ingrese datos",
                        ubdep: "Por favor ingrese datos",
                        departamento: "Por favor ingrese datos",
                        ubprov: "Por favor ingrese datos",
                        provincia: "Por favor ingrese datos",
                        ubdistr: "Por favor ingrese datos",
                        distrito: "Por favor ingrese datos"

                    },
                    submitHandler: function () {
                        var ubdep = $("#ubdep").val();
                        var departamento = $("#departamento").val();
                        var ubprov = $("#ubprov").val();
                        var provincia = $("#provincia").val();
                        var ubdistr = $("#ubdistr").val();
                        var distrito = $("#distrito").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/ubigeos/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                ubdep: ubdep,
                                departamento: departamento,
                                ubprov: ubprov,
                                provincia: provincia,
                                ubdistr: ubdistr,
                                distrito: distrito

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
