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
        field: "nm_solicitud",
        title: "Numero Solicitud",
        width: 90
    },
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
        field: "fecha_traslado",
        title: "traslado",
        width: 80
    },
    {
        field: "origen",
        title: "origen",
        width: 80
    },
    {
        field: "destino",
        title: "destino",
        width: 80
    },
    {
        field: "nombre_estado",
        title: "estado",
        width: 80
    },
    {
        field: "remitente",
        title: "remitente"
    },
    {
        field: "destinatario",
        title: "destinatario"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 130
    }

];

var datatableGuia = iniciarTabla("#tabla-guiaremisionauxiliar", "/guiaremisionauxiliar/default/lista", "#tabla-guiaremisionauxiliar-buscar", columnas);
