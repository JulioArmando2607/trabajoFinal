"use strict";
var columnas = [

    {
        field: "fecha",
        title: "Fecha Manifiesto"
    },
    {
        field: "usuario",
        title: "Usuario"
    },
    {
        field: "placa",
        title: "Placa"
    },
    {
        field: "cliente",
        title: "Cliente"
    },
    {
        field: "remitente",
        title: "Remitente"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 80
    }
];

var datatable = iniciarTabla("#tabla-manifiesto", "/manifiesto/default/lista", "#tabla-manifiesto-buscar", columnas);
