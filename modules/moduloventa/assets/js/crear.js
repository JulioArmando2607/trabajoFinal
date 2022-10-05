var tabla_productos = [];

$("#modal-venta").on("click", function () {
    console.log("ssssssssssssssssss")
    $.post(APP_URL + '/moduloventa/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Venta</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        var clientes = $("#cliente");

        $.ajax({
            type: "GET",
            dataType: 'json',
            url: APP_URL + '/moduloventa/default/list-cliente',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (response) {
                // Limpiamos el select
                clientes.find('option').remove();

                $(response).each(function (i, v) {
                    clientes.append('<option value="' + v.id_cliente + '">' + v.cliente + '</option>');
                })
            }
        });

        $("#cliente").select2({
            placeholder: "Seleccione Cliente"
        })

        $("#producto").select2({
            placeholder: "Seleccione Producto"
        })

        $("#add-producto").click(function () {
            var producto = $("#producto").val();
            var cantidad = $("#cantidad").val();

            var datosArray = producto.split('|');

            tabla_productos.push({
                id_producto: datosArray[0],
                producto: datosArray[1],
                cantidad: cantidad,
                total: (cantidad * datosArray[2])
            })

            llenarTable();
        })

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-venta").validate({
                    rules: {
                        cliente: "required"
                    },
                    messages: {
                        cliente: "Por favor ingrese dato"
                    },
                    submitHandler: function () {
                        var cliente = $("#cliente").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/moduloventa/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                cliente: cliente,
                                venta: tabla_productos
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatableVenta.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
});

function llenarTable() {
    var d_tabla = "";
    var total_pagar = 0;

    $(tabla_productos).each(function (i, v) {
        total_pagar = total_pagar + v.total;
        d_tabla = d_tabla + '<tr>\n' +
            '<th scope="row">' + (i + 1) + '</th>\n' +
            '<td>' + v.producto + '</td>\n' +
            '<td>' + v.cantidad + '</td>\n' +
            '<td>' + v.total + '</td>\n' +
            '<td>\n' +
            '<a onclick="eliminarItem(' + i + ')">\n' +
            '<i class="flaticon-delete text-danger"></i>\n' +
            '</a>\n' +
            '</td>\n' +
            '</tr>';
    })

    d_tabla = d_tabla + '<tr>\n' +
        '<td colspan="3">Total a pagar</td>\n' +
        '<td>' + total_pagar + '</td>\n' +
        '<td></td>\n' +
        '</tr>';

    $("#datos_producto").empty();
    $("#datos_producto").append(d_tabla);
}

function eliminarItem(item) {
    tabla_productos.splice(item, 1);
    llenarTable();
}

