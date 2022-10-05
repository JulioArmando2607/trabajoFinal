"use strict";
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
        field: "fecha",
        title: "Fecha",
        width: 70

    },

    {
        field: "nm_solicitud",
        title: "Numero Solicitud",
        width: 100


    },
    {
        field: "hora_recojo",
        title: "Hora",
        width: 50


    },
    {
        field: "razon_social",
        title: "Cliente",

    },

    {
        field: "tipo_servicios",
        title: "Tipo Servicio",

    },
    {
        field: "nombre_estado",
        title: "Estado",
         width: 70

    },

    {
        field: "nombres",
        title: "Conductor",

    },
    {
        field: "vehiculo",
        title: "Vehiculo",
        width: 70

    },
      {
        field: "auxiliar",
        title: "Auxiliar",
        width: 70

    },
    
    {
        field: "accion",
        title: "Acciones",
        width: 75

    }
];

var datatable = iniciarTabla("#tabla-atencionpedidos", "/atencionpedidos/default/lista", "#tabla-atencionpedidos-buscar", columnas);
