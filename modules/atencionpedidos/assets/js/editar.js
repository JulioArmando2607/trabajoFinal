var detalle_equiposiemens_edit = [];
$(document).ready(function () {


    $("#btn-actualizar").click(function () {
        $("#frm-pedidosclientes").validate({
            rules: {

                ///fecha: "required",
                //hora: "required",
                conductor: "required",
                vehiculo: "required",
                auxiliar: "required"


            },
            messages: {

                conductor: "Seleccioné",
                vehiculo: "Seleccioné",
                auxiliar: "Seleccioné"

            },
            submitHandler: function () {
                //   var fecha = $("#fecha").val();
                var conductor = $("#conductor").val();
                var vehiculo = $("#vehiculo").val();
                var id = $("#id_atencion_pedidos").val();
                var auxiliar = $("#auxiliar").val();                
                var cantidad_personas = $("#cantidad_personas").val();
                var stoka = $("#stoka").val();
 $.showLoading();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/atencionpedidos/default/update/' + id,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_atencion_pedidos: $("#id_atencion_pedidos").val(),
                        id_pedido_cliente: $("#id_pedido_cliente").val(),
                        conductor: conductor,
                        vehiculo: vehiculo,
                        auxiliar:auxiliar,
                        cantidad_personas:cantidad_personas,
                        stoka:stoka

                    },
                    success: function (response) {
                         
                        window.location.href = APP_URL + '/atencionpedidos';
                    }
                });
            }
        });
    });
});


if ($("#id_pedido_cliente").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/atencionpedidos/default/equipo-siemens',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_pedido_c: $("#id_pedido_cliente").val()
            },
            success: function (response) {
                
                console.log(response)
                response.forEach(item => {
                    detalle_equiposiemens_edit.push({
                        identificadorDetalleEdit: item.id_equipo_siemens,
                        descripcion: item.descripcion,
                        materialnumber: item.material_number,
                        oc: item.oc,
                        batch:item.batch,
                        peso:item.peso,
                        alto:item.alto,
                        ancho:item.ancho,
                        largo:item.largo,
                        volumen:item.volumen,
                        flg: 0
                    });
                });
                mostrarTablaTemporalEdit();
            }
        });
    }
var identificadorDetalleEdit = 0;
$("#agregar-detalle-edit").click(function () {
    console.log('hola')
    // let seleccion = $('select[name="producto"] option:selected').text().split('::');
    // let id_producto = $("#producto").val();
    // let producto = seleccion[0];
    //let descripcion = seleccion[1];
    //let unidad = seleccion[2];
    let oc = $("#oc").val();
    let materialnumber = $("#materialnumber").val();
    let descripcion = $("#descripcion").val();
    let batch = $("#batch").val();
    let peso = $("#peso_s").val();
    let alto = $("#alto_s").val();
    let ancho = $("#ancho_s").val();
    let largo = $("#largo_s").val();
    let volumen = $("#volumen_s").val();
    //let alto = $("#batch").val();
    // let volumen = $("#pesovol").val();

    detalle_equiposiemens_edit.push({
        identificadorDetalleEdit: identificadorDetalleEdit++,
        oc: oc,
        materialnumber: materialnumber,
        descripcion: descripcion,
        batch: batch,
        peso:peso,
        alto:alto,
        ancho:ancho,
        largo:largo,
        volumen:volumen,
        flg: 1
    });

    mostrarTablaTemporalEdit();
});

function mostrarTablaTemporalEdit() {
    let temporalDetalleGuia = "";

    detalle_equiposiemens_edit.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.oc + '</td><td>' + item.materialnumber + '</td><td>' + item.descripcion + '</td><td>' + item.batch + '</td><td>' + item.peso +'</td><td>' + item.alto + '</td><td>' +item.ancho+'</td><td>' +item.largo+'</td><td>' +item.volumen + '</td><td></td></tr>';
    });

    $("#tabla-detalle-guia").html(temporalDetalleGuia);
}


 