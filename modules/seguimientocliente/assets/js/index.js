"use strict";
var columnas = [
 
 {
        field: "nm_solicitud",
        title: "número solicitud",
        width: 90
    },
    {
        field: "numero_guia",
        title: "Numero Guia Pegaso",
        width: 80
    },
    {
        field: "gr",
        title: "Guia",
        width: 80
    },
    {
        field: "razon_social",
        title: "DESTINATARIO",
        width: 100
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
        field: "accion",
        title: "Acciones",
        width: 130
    }
];

var datatable = iniciarTabla("#tabla-seguimientocliente", "/seguimientocliente/default/lista", "#tabla-seguimientocliente-buscar", columnas);
