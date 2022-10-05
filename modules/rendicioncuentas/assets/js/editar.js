
var detalle_guia_edit = [];

$(document).ready(function () {


    if ($("#id_rendicion_cuentas").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/rendicioncuentas/default/detalle-cuentas',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_rendicion_cuentas: $("#id_rendicion_cuentas").val()
            },

            success: function (response) {
                response.forEach(item => {
                    detalle_guia_edit.push({
                        identificadorDetalle: item.id_detalle_rendicion_cuentas,
                        fecha: item.fecha,
                        proveedor: item.proveedor,
                        nm_documento: item.nm_documento,
                        concepto: item.concepto,
                        monto:  item.monto,
                        flg: 0
                    });
                });
                mostrarTablaTemporalEdit();
            }
        });
    }


    $("#btn-actualizar").click(function () {
        $("#frm-rendicion-cuenta-edit").validate({
            rules: {

                fecha: "required",
                nr_operacion: "required",
                abono_cuenta_de: "required",

            },
            messages: {
                fecha: "Por favor ingrese fecha",
                abono_cuenta_de: "Por favor seleccioné",


            },
            submitHandler: function () {
                var fecha = $("#fecha").val();
                var nr_operacion = $("#nr_operacion").val();
                var abono_cuenta_de = $("#abono_cuenta_de").val();
                var rinde = $("#rinde").val();
                var diferencia_depo = $("#diferencia_depo").val();
                var importe_entregado = $("#importe_entregado").val();


                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/rendicioncuentas/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_rendicion_cuentas: $("#id_rendicion_cuentas").val(),
                        fecha: fecha,
                        nr_operacion: nr_operacion,
                        abono_cuenta_de: abono_cuenta_de,
                        rinde: rinde,
                        importe_entregado: importe_entregado,
                        diferencia_depo: diferencia_depo

                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/rendicioncuentas';
                    }
                });
            }
        });
    });
});

$("#agregar-detalle-rc-edit").click(function () {
    var fecha_rc = $("#fecha_rc").val();
    var proveedor_rc = $("#proveedor_rc").val();
    var nm_documento_rc = $("#nm_documento_rc").val();
    var concepto_rc = $("#concepto_rc").val();
    var monto_rc = $("#monto_rc").val();


    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/rendicioncuentas/default/reg-detalle',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            id_rendicion_cuentas: $("#id_rendicion_cuentas").val(),
            fecha_rc: fecha_rc,
            proveedor_rc: proveedor_rc,
            nm_documento_rc: nm_documento_rc,
            concepto_rc: concepto_rc,
            monto_rc: monto_rc

        },
        success: function (response) {
            console.log('', response);
            detalle_guia_edit=[];
            resetear();
            if ($("#id_rendicion_cuentas").val() != null) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/rendicioncuentas/default/detalle-cuentas',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_rendicion_cuentas: $("#id_rendicion_cuentas").val()
                    },

                    success: function (response) {
                        response.forEach(item => {
                            detalle_guia_edit.push({
                                identificadorDetalle: item.id_detalle_rendicion_cuentas,
                                fecha: item.fecha,
                                proveedor: item.proveedor,
                                nm_documento: item.nm_documento,
                                concepto: item.concepto,
                                monto:  item.monto,
                                flg: 0
                            });
                        });
                        mostrarTablaTemporalEdit();
                    }
                });
            }

            //resetear();
            //  window.location.href = APP_URL + '/rendicioncuentas';
        }
    });

});
function mostrarTablaTemporalEdit() {
    let temporalDetalleGuia = "";

    detalle_guia_edit.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.fecha + '</td><td>' + item.proveedor + '</td><td>' + item.nm_documento + '</td><td>' + item.concepto + '</td><td>' + item.monto +  '</td><td><i class="flaticon-edit text-primary bg-hover-light" onclick="funcionEditarRendicionDetalle(' + item.identificadorDetalle + ')"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuiaEdit(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-rendicioncuenta-ed").html(temporalDetalleGuia);

}
function eliminarTemporalDetalleGuiaEdit(id) {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/rendicioncuentas/default/delete-detalle',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            id: id,
        },
        success: function (response) {
            console.log('', response);
            detalle_guia_edit=[];
            resetear();
            if ($("#id_rendicion_cuentas").val() != null) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/rendicioncuentas/default/detalle-cuentas',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_rendicion_cuentas: $("#id_rendicion_cuentas").val()
                    },

                    success: function (response) {
                        response.forEach(item => {
                            detalle_guia_edit.push({
                                identificadorDetalle: item.id_detalle_rendicion_cuentas,
                                fecha: item.fecha,
                                proveedor: item.proveedor,
                                nm_documento: item.nm_documento,
                                concepto: item.concepto,
                                monto:  item.monto,
                                flg: 0
                            });
                        });
                        mostrarTablaTemporalEdit();
                    }
                });
            }

            //resetear();
            //  window.location.href = APP_URL + '/rendicioncuentas';
        }
    });

    mostrarTablaTemporalEdit();
}
function resetear(){
    $("#fecha_rc").val('');
    $("#proveedor_rc").val('');
    $("#nm_documento_rc").val('');
    $("#concepto_rc").val('');
    $("#monto_rc").val('');

}
function funcionEditarRendicionDetalle(id) {
    $.post(APP_URL + '/rendicioncuentas/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Detalle Rendicion</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-edit-detalle").validate({
                    rules: {

                        fecha: "required",
                        nr_operacion: "required",
                        abono_cuenta_de: "required",

                    },
                    messages: {
                        fecha: "Por favor ingrese fecha",
                        //nr_operacion: "Por favor ingrese datos",
                        abono_cuenta_de: "Por favor seleccioné",
                        //nombre_via: "",

                    },
                    submitHandler: function () {
                        var fecha = $("#fecha_ed").val();
                        var proveedor = $("#proveedor").val();
                        var nm_documento = $("#nm_documento").val();
                        var concepto = $("#concepto").val();
                        var monto = $("#monto").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/rendicioncuentas/default/update-detalle',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_detalle_rendicion_cuentas: id,
                                fecha:fecha,
                                proveedor:proveedor,
                                nm_documento:nm_documento,
                                concepto:concepto,
                                monto:monto,
                            },
                            success: function (response) {
                                 console.log(response)
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        url: APP_URL + '/rendicioncuentas/default/detalle-cuentas',
                                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                        data: {
                                            id_rendicion_cuentas: $("#id_rendicion_cuentas").val()
                                        },

                                        success: function (response) {
                                            detalle_guia_edit=[];
                                            detalle_guia_edit.pop();
                                            response.forEach(item => {
                                                detalle_guia_edit.push({
                                                    identificadorDetalle: item.id_detalle_rendicion_cuentas,
                                                    fecha: item.fecha,
                                                    proveedor: item.proveedor,
                                                    nm_documento: item.nm_documento,
                                                    concepto: item.concepto,
                                                    monto:  item.monto,
                                                    flg: 0
                                                });
                                            });

                                            mostrarTablaTemporalEdit();

                                        }
                                    });

                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                //datatable.reload()


                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}

