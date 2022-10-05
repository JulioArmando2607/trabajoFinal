var estado=1;
$(document).ready(function () {
    $("#idCliente").select2({
        placeholder: "Seleccioné Cliente"
    });
    $("#idEstado").select2({
        placeholder: "Seleccioné Estado"
    });
    $("#idProvincia").select2({
        placeholder: "Seleccioné Provincia"
    });
    $("#idAgente").select2({
        placeholder: "Seleccioné Agente"
    });


    $("#total").keyup(function () {
        var valventa = $("#total").val()/1.18;
        var igv = valventa * 0.18;
        console.log(igv)

        $("#valor_venta").val(valventa.toFixed(2));
        $("#igv").val(igv.toFixed(2));
        $("#monto_diferencia").val($("#total").val()-$("#monto_depositado").val())

    });

    $("#monto_depositado").keyup(function () {

        var monto_diferencia = $("#total").val()-$("#monto_depositado").val();
       // var igv = valventa * 0.18;
       // console.log(igv)

        $("#monto_diferencia").val(monto_diferencia.toFixed(2));
       if($("#monto_diferencia").val()==0.00){
           console.log($("#monto_diferencia").val());
          estado=30;
          console.log(estado);
       }

    });


    $("#btn-guardar").click(function () {
        $("#frm-registro-venta").validate({
            rules: {
            },
            messages: {
            },
            submitHandler: function () {
                $('#btn-guardar').attr('disabled', true);
                var fecha = $("#fecha").val();
                var serie = $("#serie").val();
                var factura = $("#factura").val();
                var idCliente = $("#idCliente").val();
                var valor_venta = $("#valor_venta").val();
                var igv = $("#igv").val();
                var total = $("#total").val();
                ///var fecha_cancelacion = $("#fecha_cancelacion").val();
                var monto_depositado = $("#monto_depositado").val();
                var monto_diferencia = $("#monto_diferencia").val();
                //var idEstado = 1;
                //var gr = $("#gr").val();
                //var idProvincia = $("#idProvincia").val();
               // var idAgente = $("#idAgente").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/registroventa/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        fecha: fecha,
                        serie: serie,
                        factura: factura,
                        idCliente: idCliente,
                        valor_venta: valor_venta,
                        igv: igv,
                        total: total,
                        ///fecha_cancelacion: fecha_cancelacion,
                        monto_depositado: monto_depositado,
                        monto_diferencia: monto_diferencia,
                        idEstado: estado,
                       // gr: gr,
                       // idProvincia:idProvincia,
                       // idAgente: idAgente

                    },
                    beforeSend: function () {
                        $.showLoading();
                    },
                    success: function (response) {
                        notificacion('Accion realizada con exito', 'success');
                        window.location.href = APP_URL + '/registroventa';
                        datatableRegistroventa.refresh()
                    }, error: function (error) {
                        $('#btn-guardar').attr('disabled', false);
                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });

});

