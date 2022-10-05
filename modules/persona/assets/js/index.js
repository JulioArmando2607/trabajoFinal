"use strict";
var columnas = [
    {
        field: "dni",
        title: "DNI",
        width: 75
    },
    {
        field: "nombres",
        title: "Nombres"
    },
    {
        field: "apellido_paterno",
        title: "Apellido Paterno"
    },
    {
        field: "apellido_materno",
        title: "Apellido Materno"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-persona", "/persona/default/lista", "#tabla-persona-buscar", columnas);
