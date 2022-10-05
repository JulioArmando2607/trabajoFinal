"use strict";
var columnas = [
    {
        field: "numero",
        title: "Número"
    },{
        field: "cliente",
        title: "Cliente"
    },{
        field: "monto",
        title: "Monto"
    },{
        field: "estado",
        title: "Estado"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatableVenta = iniciarTabla("#tabla-moduloventa", "/moduloventa/default/lista", "#tabla-moduloventa-buscar", columnas);
