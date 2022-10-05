"use strict";
var columnas = [
   
 
    {
        field: "serie",
        title: "Serie"
    },
   
   
    {
        field: "correlativo",
        title: "Correlativo"
    },
    {
         field: "documento_electronico_aplicar",
        title: "Documento Electronico Aplicado"
        
    }
    ,
   //
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatableUsuario = iniciarTabla("#tabla-notascredito", "/facturas/notascredito/lista", "#tabla-notascredito-buscar", columnas);

$("#modal-ncredito").on("click", function () {
    $.post(APP_URL + '/facturas/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Nota Credito</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-notacredito").validate({
                    rules: {

                        fechaemision: "required",
                        tipo_nota_cred: "required",
                        s_series: "required",
                        correlativo: "required",
                        sustento_nota_credito: "required",
                        numero_doc: "required",
                        tipo_doc_cliente: "required",
                        total: "required",
                        nombre_razon_cliente: "required",

                    },
                    messages: {

                        fechaemision: "Por favor ingrese datos",
                        tipo_nota_cred: "Por favor ingrese datos",
                        s_series: "Por favor ingrese datos",
                        correlativo: "Por favor ingrese datos",
                        sustento_nota_credito: "Por favor ingrese datos",
                        numero_doc: "Por favor ingrese datos",
                        tipo_doc_cliente: "Por favor ingrese datos",
                        total: "Por favor ingrese datos",
                        nombre_razon_cliente: "Por favor ingrese datos",

                    },
                    submitHandler: function () {

                        var fechaemision = $("#fechaemision").val();
                        var tipo_nota_cred = $("#tipo_nota_cred").val();
                        var serie_doc = $("#serie_doc").val();
                        var correlativo = $("#correlativo").val();
                        var sustento_nota_credito = $("#sustento_nota_credito").val();
                        var numero_doc = $("#numero_doc").val();
                        var tipo_doc_cliente = $("#tipo_doc_cliente").val();
                        var total = $("#total").val();
                        var nombre_razon_cliente = $("#nombre_razon_cliente").val();
                        var tipo_comprobante = $("#tipo_comprobante").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/facturas/default/nota-credito',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                fechaemision: fechaemision,
                                tipo_nota_cred: tipo_nota_cred,
                                serie_doc: serie_doc,
                                correlativo: correlativo,
                                sustento_nota_credito: sustento_nota_credito,
                                numero_doc: numero_doc,
                                tipo_doc_cliente: tipo_doc_cliente,
                                total: total,
                                nombre_razon_cliente: nombre_razon_cliente,
                                tipo_comprobante: tipo_comprobante


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

function NotaCredito(id) {
    $.post(APP_URL + '/facturas/default/get-modal-notacredito/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>NOTA CREDITO</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });


        $("#btn-emitir").click(function () {
            $("#form-notacredito ").validate({

                submitHandler: function () {
                    var fechaemision = $("#fechaemision").val();
                    var tipo_nota_cred = $("#tipo_nota_cred").val();
                    var numero_doc = $("#numero_doc").val();
                    var correlativo = $("#correlativo").val();
                    var tipo_doc_cliente = $("#tipo_doc_cliente").val();
                    var serie_doc = $("#serie_doc").val();
                    var nombre_razon_cliente = $("#nombre_razon_cliente").val();
                    var total = $("#total").val();
                    var tipo_comprobante = $("#tipo_comprobante").val();
                    var id_guia_ventas = $("#id_guia_ventas").val();


                    $.showLoading();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/facturas/default/nota-credito-f',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                            id: id,

                            tipo_nota_cred:tipo_nota_cred,
                            fechaemision:fechaemision,
                            numero_doc:numero_doc,
                            correlativo:correlativo,
                            tipo_doc_cliente:tipo_doc_cliente,
                            serie_doc:serie_doc,
                            nombre_razon_cliente:nombre_razon_cliente,
                            total:total,
                            tipo_comprobante:tipo_comprobante,
                            id_guia_ventas:id_guia_ventas,



                        },

                        success: function (response) {
                            $.hideLoading();
                            if (response) {

                              //  alert('vamos' + response)
                                bootbox.hideAll();
                                datatableUsuario.reload();
                                //      window.location.href = "facturas/default/imprimir-factura/" + response;
                            } else if (response) {

                            }
                        }


                    });
                }
            });
        });

    }, 'json');
}



function funcionVolverNota(id) {
    $.post(APP_URL + '/facturas/default/get-modal-notacreditov/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>NOTA CREDITO</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });


        $("#btn-emitir").click(function () {
            $("#form-notacredito-n").validate({

                submitHandler: function () {
                
                    $.showLoading();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/facturas/default/nota-credito-fv',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                            id: id,                            
                       
                        },

                        success: function (response) {
                            $.hideLoading();
                            datatableUsuario.reload();
                            if (response) {

                              //  alert('vamos' + response)
                                bootbox.hideAll();
                                datatableUsuario.reload();
                                //      window.location.href = "facturas/default/imprimir-factura/" + response;
                            } else if (response) {

                            }
                        }


                    });
                }
            });
        });

    }, 'json');
}
