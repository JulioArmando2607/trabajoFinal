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
        field: "check",
        title: "",
        width: 50},
    {
        field: "nm_solicitud",
        title: "Numero Solicitud",
        width: 90
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
    },
];



var datatableGuia = iniciarTabla("#tabla-procesarguias", "/procesarguias/default/lista", "#tabla-procesarguias-buscar", columnas);


$("#pendguias").change(function () {
    datatableGuia.search($(this).val().toLowerCase(), "generalSearch")
});
/*       $("#pend_guias").change(function () {
 datatableGuia.search($(this).val().toLowerCase(), "nm_solicitud")
 }); */