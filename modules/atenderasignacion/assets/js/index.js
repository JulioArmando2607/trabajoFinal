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
        title: "SOLICITUD",
        width: 80
    },
    {
        field: "razon_social",
        title: "Entidad",
        width: 150
    },
    {
        field: "fecha",
        title: "FECHA",
        width: 80
    },

    {
        field: "hora_recojo",
        title: "HORA RECOJO",
        width: 80
    },

    {
        field: "contacto",
        title: "Contacto",
        width: 80
    },

    {
        field: "telefono",
        title: "Telefono",
        width: 80
    },
    {
        field: "accion",
        title: "Acciones",
        width: 130
    }
];

var datatableGuia = iniciarTabla("#tabla-atenderasignacion", "/atenderasignacion/default/lista", "#tabla-atenderasignacion-buscar", columnas);
