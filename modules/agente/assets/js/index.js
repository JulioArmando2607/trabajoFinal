"use strict";
var columnas = [
   
    {
        field: "cuenta",
        title: "Nombre Agente"
    },
    {
        field: "agente",
        title: "Agente"
    },
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-agente", "/agente/default/lista", "#tabla-agente-buscar", columnas);
