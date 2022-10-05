"use strict";
var columnas = [

    {
        field: "nombre_marca",
        title: "nombre_marca"
    },
    {
        field: "placa",
        title: "placa"
    },
    {
        field: "descripcion",
        title: "descripcion"
    },

    {
        field: "incripcion",
        title: "incripcion"
    },
 
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-vehiculos", "/vehiculos/default/lista", "#tabla-vehiculos-buscar", columnas);
