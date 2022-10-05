function funcionEditar(id) {
    $.post(APP_URL + '/liquidacion/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Montos</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        if ($("#costo_vlorliquidacion").val() == '') {
            $("#costo_vlorliquidacion").val(0.0);

        }
        var cost_vl= $("#costo_vlorliquidacion").val();
        $("#peso_liquidacion").keyup(function () {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/liquidacion/default/calculo-tarifa',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id: id,
                    peso_liquidacion: $("#peso_liquidacion").val(),

                },
                success: function (response) {
                    $("#costo_liquidacion").val(response['costo']);
                    $("#peso_exceso_liquidacion").val(response['peso_exceso']);
                   cost_vl=response['costo'];


                }
            });


        });
        $("#reembarque_liquidacion").keyup(function () {
            var reembarque_liquidacion = $("#reembarque_liquidacion").val();
            var monto = parseFloat(reembarque_liquidacion) + parseFloat(cost_vl);
            if (reembarque_liquidacion == '') {

            } else {

                $("#costo_liquidacion").val(monto);
            }

        });




        $(document).ready(function () {
            $("#btn-actualizar-ed").click(function () {
                $("#form-editar-liqui").validate({
                    rules: {},
                    messages: {},
                    submitHandler: function () {
                        var costoLiquidacion = $("#costo_liquidacion").val();
                        var reembarque = $("#reembarque_liquidacion").val();
                        var obs_reembarque = $("#obs_reembarque").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/liquidacion/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id: id,
                                costoLiquidacion: costoLiquidacion,
                                reembarque: reembarque,
                                obs_reembarque: obs_reembarque,
                                peso_liquidacion: $("#peso_liquidacion").val(),
                                peso_exceso_liquidacion: $("#peso_exceso_liquidacion").val(),

                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                TotalesLiquidacions()
                                datatableGuiass.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}

