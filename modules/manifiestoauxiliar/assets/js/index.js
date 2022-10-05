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

var datatable = iniciarTabla("#tabla-manifiestoauxiliar", "/manifiestoauxiliar/default/lista", "#tabla-manifiestoauxiliar-buscar", columnas);
