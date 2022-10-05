$("#abono_cuenta_de").select2({
    placeholder: "Seleccioné"})

$("#rinde").select2({
    placeholder: "Seleccioné"})

var detalle_guia_rc = [];

$(document).ready(function () {
    $("#btn-guardar").click(function () {
        $("#frm-rendicion-cuenta").validate({
            rules: {

                fecha: "required",
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
                    url: APP_URL + '/rendicioncuentas/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {

                        fecha: fecha,
                        nr_operacion: nr_operacion,
                        abono_cuenta_de: abono_cuenta_de,
                        rinde: rinde,
                        importe_entregado: importe_entregado,
                        diferencia_depo: diferencia_depo,
                        detalle_guia_rc: detalle_guia_rc

                    },
                    success: function (response) {

                        if (response) {
                            notificacion('Accion realizada con exito', 'success')
                            window.location.href = APP_URL + '/rendicioncuentas';
                        } else {
                            notificacion('Error al guardar datos', 'error');
                        }
                        datatableRendicioncuentas.reload()
                    }
                });
            }
        });
    });
});

var edit = 0;
var identificadorDetalleRC = 0;
$("#agregar-detalle-rc").click(function () {


    let fecha_d_rc = $("#fecha_d_rc").val();
    let proveedor_d_rc = $("#proveedor_d_rc").val();
    let ndocumento_d_rc = $("#ndocumento_d_rc").val();
    let concepto_d_rc = $("#concepto_d_rc").val();
    let monto_d_rc = $("#monto_d_rc").val();

    $("#fecha_d_rc").val('');
    $("#proveedor_d_rc").val('');
    $("#ndocumento_d_rc").val('');
    $("#concepto_d_rc").val('');
    $("#monto_d_rc").val('');

    if (edit == 0) {
        detalle_guia_rc.push({
            identificadorDetalle: identificadorDetalleRC++,
            fecha_d_rc: fecha_d_rc,
            proveedor_d_rc: proveedor_d_rc,
            ndocumento_d_rc: ndocumento_d_rc,
            concepto_d_rc: concepto_d_rc,
            monto_d_rc: monto_d_rc,

        });
    } else {
        detalle_guia_rc.push({
            identificadorDetalle: edit,
            fecha_d_rc: fecha_d_rc,
            proveedor_d_rc: proveedor_d_rc,
            ndocumento_d_rc: ndocumento_d_rc,
            concepto_d_rc: concepto_d_rc,
            monto_d_rc: monto_d_rc,

        });
        edit = 0;
    }

    detalle_guia_rc = generateData(detalle_guia_rc);

    mostrarTablaTemporalRC()
     var total = 0;
    for (let i = 0; i < detalle_guia_rc.length ; i++) {
        var value = parseFloat(detalle_guia_rc[i].monto_d_rc);
        total += value;

    }

    console.log($("#diferencia_depo").val($("#importe_entregado").val()-total))
});

function mostrarTablaTemporalRC() {
    let temporalDetalleGuia = "";

    detalle_guia_rc.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.fecha_d_rc + '</td><td>' + item.proveedor_d_rc + '</td><td>' + item.ndocumento_d_rc + '</td><td>' + item.concepto_d_rc + '</td><td>' + item.monto_d_rc + '</td><td><i class="flaticon-edit text-primary bg-hover-light" onclick="editarDetalleRC(' + item.identificadorDetalle + ')"></i> <i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuiaRC(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-rc").html(temporalDetalleGuia);
}

function eliminarTemporalDetalleGuiaRC(id) {
    var data = detalle_guia_rc.filter(function (item) {
        return +item.identificadorDetalle !== id;
    });

    detalle_guia_rc = data;

    mostrarTablaTemporalRC();
}

function editarDetalleRC(id) {
    edit = +id;
    let data = detalle_guia_rc.filter(function (item) {
        return +item.identificadorDetalle == +id;
    });

    $("#fecha_d_rc").val(data[0].fecha_d_rc);
    $("#proveedor_d_rc").val(data[0].proveedor_d_rc);
    $("#ndocumento_d_rc").val(data[0].ndocumento_d_rc);
    $("#concepto_d_rc").val(data[0].concepto_d_rc);
    $("#monto_d_rc").val(data[0].monto_d_rc);
}

function generateData(array) {
    let tempMap = array.map(item => {
        return [item.identificadorDetalle, item]
    });

    let tempMapArr = new Map(tempMap); // Pares de clave y valor

    let unicos = [...tempMapArr.values()]; // Conversión a un array

    return unicos;
}


$("#importe_entregado").keyup(function () {
    var total = 0;
    for (let i = 0; i < detalle_guia_rc.length ; i++) {
        var value = parseFloat(detalle_guia_rc[i].monto_d_rc);
        total += value;

    }


    setTimeout(() => {
        $("#diferencia_depo").val(parseFloat($("#importe_entregado").val()-total))
    }, 0);

});