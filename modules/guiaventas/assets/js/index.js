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
        field: "numero_guia",
        title: "número",
        width: 80
    },
    {
        field: "fecha",
        title: "fecha",
        width: 80
    },

    {
        field: "forma_pago",
        title: "Forma Pago",
        width: 80
    },
    {
        field: "nombre_estado",
        title: "Estado",
        width: 80
    },

    {
        field: "tipo_entrega",
        title: "Tipo Entrega",
        width: 80
    },

    {

        field: "destino",
        title: "Destino",
        width: 80
    },

    {
        field: "accion",
        title: "Acciones",
        width: 130
    }
];

var datatableGuia = iniciarTabla("#tabla-guia-ventas", "/guiaventas/default/lista", "#tabla-guia-ventas-buscar", columnas);
