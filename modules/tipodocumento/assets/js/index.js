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
        field: "documento",
        title: "Documento"
    },
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-tipo-documento", "/tipodocumento/default/lista", "#tabla-tipo-documento-buscar", columnas);
