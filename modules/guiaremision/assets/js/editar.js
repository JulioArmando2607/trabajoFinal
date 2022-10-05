var detalle_guia_edit = [];
var detalle_guia_edit_rc = [];

var detalle_guia_delete = [];
var detalle_guia_delete_rc = [];

$(document).ready(function () {

    $("#remitente").select2({
        placeholder: "SeleccionÃ© Remitente"
    });

    $("#destinatario").select2({
        placeholder: "SeleccionÃ© Destinatario"
    });

    $("#cliente").select2({
        placeholder: "SeleccionÃ© Cliente"
    });

    $("#agente").select2({
        placeholder: "SeleccionÃ© Agente"
    });

    $("#conductor").select2({
        placeholder: "SeleccionÃ© Conductor"
    });

    $("#vehiculo").select2({
        placeholder: "SeleccionÃ© Vehiculo"
    });
    $("#direccion_partida").select2({
        placeholder: "SeleccionÃ© Direccion Partida"
    });

    $("#direccion_llegada").select2({
        placeholder: "SeleccionÃ© Direccion Llegada"
    });
    $("#transportista").select2({
        placeholder: "SeleccionÃ©"
    });


    if ($("#remitente").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $("#remitente").val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
                var id_direccion_partida = $("#id_direccion_partida").val();
                $("#direccion_partida option[value='" + id_direccion_partida + "']").attr("selected", true);
            }
        });
    }

    $("#remitente").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_partida").html(response);
            }
        });
    });

    if ($("#destinatario").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $("#destinatario").val()
            },
            success: function (response) {
                $("#direccion_llegada").html(response);
                var id_direccion_llegada = $("#id_direccion_llegada").val();
                $("#direccion_llegada option[value='" + id_direccion_llegada + "']").attr("selected", true);
            }
        });
    }

    $("#destinatario").change(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/buscar-direccion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_entidad: $(this).val()
            },
            success: function (response) {
                $("#direccion_llegada").html(response);
            }
        });
    });

    if ($("#id_guia").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/detalle-guia',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_guia: $("#id_guia").val()
            },
            success: function (response) {
                response.forEach(item => {
                    detalle_guia_edit.push({
                        identificadorDetalle: item.id_detalle_guia,
                        id_producto: item.id_producto,
                        producto: item.producto,
                        descripcion: item.descripcion,
                        unidad: item.unidad_medida,
                        cantidad: item.cantidad,
                        peso: item.peso,
                        volumen: item.volumen,
                        largo: item.largo,
                        ancho: item.ancho,
                        alto: item.alto,
                        flg: 0
                    });
                });
                mostrarTablaTemporalEdit();
            }
        });
    }

    if ($("#id_guia").val() != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/guiaremision/default/guia-cliente',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                id_guia: $("#id_guia").val()
            },
            success: function (response) {

                response.forEach(item => {
                    detalle_guia_edit_rc.push({
                        identificadorDetalle: item.id_guia_remision_cliente,
                        grs: item.grs,
                        gr: item.gr,
                        ft: item.ft,
                        oc: item.oc,
                        id_tipo_carga: item.id_tipo_carga,
                        tipo_carga: item.tipo_carga,
                        descripcion: item.descripcion,

                        cantidad: item.cantidad,
                        peso: item.peso,
                        volumen: item.volumen,
                        largo: item.largo,
                        ancho: item.ancho,
                        alto: item.alto,
                        flg: 0
                    });
                });
                mostrarTablaTemporalEditRC();
            }
        });
    }

    $("#btn-actualizar").click(function () {
        $("#frm-guia-remision").validate({
            rules: {
                serie: "required",
                numero: "required",
                fecha: "required",
                traslado: "required",
                via: "required",
                cliente: "required",
                agente: "required",
                remitente: "required",
                direccion_partida: "required",
                destinatario: "required",
                direccion_llegada: "required",
                conductor: "required",
                vehiculo: "required",
            },
            messages: {
                serie: "Ingrese datos",
                numero: "Ingrese datos",
                fecha: "Ingrese datos",
                traslado: "Ingrese datos",
                via: "Ingrese datos",
                cliente: "SeleccionÃ©",
                agente: "SeleccionÃ©",
                remitente: "SeleccionÃ©",
                direccion_partida: "SeleccionÃ©",
                destinatario: "SeleccionÃ©",
                direccion_llegada: "SeleccionÃ©",
                conductor: "SeleccionÃ©",
                vehiculo: "SeleccionÃ©",
            },
            submitHandler: function () {
                var serie = $("#serie").val();
                var numero = $("#numero").val();
                var fecha = $("#fecha").val();
                var traslado = $("#traslado").val();
                var via = $("#via").val();
                var cliente = $("#cliente").val();
                var agente = $("#agente").val();
                var remitente = $("#remitente").val();
                var direccion_partida = $("#direccion_partida").val();
                var destinatario = $("#destinatario").val();
                var direccion_llegada = $("#direccion_llegada").val();
                var conductor = $("#conductor").val();
                var vehiculo = $("#vehiculo").val();
                var transportista = $("#transportista").val();
                var guia_remision = $("#guia_remision").val();
                var factura = $("#factura").val();
                var importe = $("#importe").val();
                var comentario = $("#comentario").val();
                var via_tipo = $("#via_tipo").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/guiaremision/default/update',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {
                        id_guia: $("#id_guia").val(),
                        serie: serie,
                        numero: numero,
                        fecha: fecha,
                        traslado: traslado,
                        via: via,
                        via_tipo: via_tipo,
                        cliente: cliente,
                        agente: agente,
                        remitente: remitente,
                        direccion_partida: direccion_partida,
                        destinatario: destinatario,
                        direccion_llegada: direccion_llegada,
                        conductor: conductor,
                        vehiculo: vehiculo,
                        transportista: transportista,
                        guia_remision: guia_remision,
                        factura: factura,
                        importe: importe,
                        comentario: comentario,
                        detalle_guia_edit: detalle_guia_edit,
                        detalle_guia_edit_rc: detalle_guia_edit_rc,
                        detalle_guia_delete: detalle_guia_delete,
                        detalle_guia_delete_rc: detalle_guia_delete_rc
                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/guiaremision';
                    }
                });
            }
        });
    });
});

var identificadorDetalleEdit = 0;
$("#agregar-detalle-edit").click(function () {
    let seleccion = $('select[name="producto"] option:selected').text().split('::');
    let id_producto = $("#producto").val();
    let producto = seleccion[0];
    let descripcion = seleccion[1];
    let unidad = seleccion[2];
    let cantidad = $("#cantidad").val();
    let peso = $("#peso").val();
    let volumen = $("#pesovol").val();
    let largo = $("#largo").val();
    let ancho = $("#ancho").val();
    let alto = $("#alto").val();

    detalle_guia_edit.push({
        identificadorDetalle: identificadorDetalleEdit++,
        id_producto: id_producto,
        producto: producto,
        descripcion: descripcion,
        unidad: unidad,
        cantidad: cantidad,
        peso: peso,
        volumen: volumen,
        largo: largo,
        ancho: ancho,
        alto: alto,
        flg: 1
    });

    mostrarTablaTemporalEdit();
});

function eliminarTemporalDetalleGuiaEdit(id) {
    var data = detalle_guia_edit.filter(function (item) {
        return +item.identificadorDetalle !== id;
    });

    var resultado = detalle_guia_edit.find(item => item.identificadorDetalle == id);
    detalle_guia_delete.push(resultado);

    detalle_guia_edit = data;

    mostrarTablaTemporalEdit();
}

function mostrarTablaTemporalEdit() {
    let temporalDetalleGuia = "";

    detalle_guia_edit.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.producto + '</td><td>' + item.descripcion + '</td><td>' + item.unidad + '</td><td>' + item.cantidad + '</td><td>' + item.peso + '</td><td>' + item.volumen + '</td><td><i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuiaEdit(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-guia").html(temporalDetalleGuia);
}

/////////////////////////////////////////////////////////////

var identificadorDetalleRC = 0;
$("#agregar-detalle-rc-edit").click(function () {
    let grs = $("#grserie").val();
    let gr = $("#gr").val();
    let ft = $("#ft").val();
    let oc = $("#oc").val();
    let id_tipo_carga = $("#tipo_carga").val();
    let tipo_carga = $('select[name="tipo_carga"] option:selected').text()
    let descripcion = $("#descripcion").val();

    let cantidad = $("#cantidad").val();
    let peso = $("#peso").val();
    let alto = $("#alto").val();
    let ancho = $("#ancho").val();
    let largo = $("#largo").val();
    let volumen = $("#pesovol").val();

    detalle_guia_edit_rc.push({
        identificadorDetalle: identificadorDetalleRC++,
        grs: grs,
        gr: gr,
        ft: ft,
        oc: oc,
        id_tipo_carga: id_tipo_carga,
        tipo_carga: tipo_carga,
        descripcion: descripcion,

        cantidad: cantidad,
        peso: peso,
        volumen: volumen,
        alto: alto,
        ancho: ancho,
        largo: largo,
        flg: 1
    });

    mostrarTablaTemporalEditRC();
});

function eliminarTemporalDetalleGuiaEditRC(id) {
    var data = detalle_guia_edit_rc.filter(function (item) {
        return +item.identificadorDetalle !== id;
    });

    var resultado = detalle_guia_edit_rc.find(item => item.identificadorDetalle == id);
    detalle_guia_delete_rc.push(resultado);

    detalle_guia_edit_rc = data;

    mostrarTablaTemporalEditRC();
}

function mostrarTablaTemporalEditRC() {
    let temporalDetalleGuia = "";

    detalle_guia_edit_rc.forEach(item => {
        temporalDetalleGuia += '<tr><td>' + item.grs + '</td><td>' + item.gr + '</td><td>' + item.ft + '</td><td>' + item.oc + '</td><td>' + item.cantidad + '</td><td>' + item.peso+ '</td><td>' + item.largo+ '</td><td>' + item.ancho+ '</td><td>' + item.alto+ '</td><td>' + item.volumen + '</td><td>' + item.tipo_carga + '</td><td>' + item.descripcion + '</td><td><i class="flaticon-delete text-danger bg-hover-light" onclick="eliminarTemporalDetalleGuiaEditRC(' + item.identificadorDetalle + ')"></i></td></tr>';
    });

    $("#tabla-detalle-guia-rc").html(temporalDetalleGuia);
}