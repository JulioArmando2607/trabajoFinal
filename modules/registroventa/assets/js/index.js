
"use strict";

/**
 *Funcion para activar el loader antes de cargar la vista
 */
window.onbeforeunload = function () {
    $.showLoading();
}

/**
 * Funcion para validar la carga de pagina para el loader
 */
window.onload = function () {
    $.hideLoading();
}

var columnas = [
        {
        field: "fecha_emision",
        title: "fecha emision",
        width: 90
    },
    {
        field: "nComprobante",
        title: "número Comprobante",
        width: 100
    },
    {
        field: "cliente",
        title: "cliente",
        width: 190
    },
 
    {
        field: "valor_venta",
        title: "valor venta",
        width: 80
    },
    {
        field: "igv",
        title: "igv",
        width: 80
    },
    {
        field: "total",
        title: "total",
        width: 80
    },
        {
        field: "fecha_cancelacion",
        title: "fecha cancelacion",
        width: 100
    },
    {
        field: "monto_depositado",
        title: "monto depositado"
    },
    {
        field: "monto_diferencia",
        title: "monto diferencia"
    },
     {
        field: "nombre_estado",
        title: "nombre estado",
         width: 100
    },
    {
        field: "accion",
        title: "Acciones",
        width: 100
    }
];

var datatableRegistroventa = iniciarTabla("#tabla-registro-venta", "/registroventa/default/lista", "#tabla-registro-venta-buscar", columnas);

$("#fecha_liquidacion").change(function () {
    console.log($("#fecha_liquidacion").val());
    buscarLiquidado()
});

function buscarLiquidado() {

    if($("#fecha_liquidacion").val()==''){
        datatableRegistroventa.search($("#fecha_liquidacion").val()+"".toLowerCase(), "mes")
    } else{
        datatableRegistroventa.search($("#fecha_liquidacion").val()+"-00".toLowerCase(), "mes")
    }
        //datatableRegistroventa.search($("#fecha_liquidacion").val()+"-00".toLowerCase(), "mes")



}
