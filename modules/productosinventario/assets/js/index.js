"use strict";
var columnas = [

    {
        field: "item",
        title: "item"
    },
    {
        field: "nombre",
        title: "nombre producto"
    },
    {
        field: "descripcion",
        title: "descripcion"
    },
    {
        field: "precio",
        title: "precio unitario"
    },
    {
        field: "cantidad",
        title: "cantidad"
    },
    {
        field: "medida",
        title: "medida"
    },

    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-productosinventario", "/productosinventario/default/lista", "#tabla-productosinventario-buscar", columnas);
