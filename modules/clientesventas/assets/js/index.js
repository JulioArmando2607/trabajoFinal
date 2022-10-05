"use strict";
var columnas = [
 
    {
        field: "razon_social",
        title: "Cliente"
    },
    {
        field: "documento",
        title: "Tipo Documento"
    },
    {
        field: "numero_documento",
        title: "Numero Documento"
    },
 
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-clientesventas", "/clientesventas/default/lista", "#tabla-clientesventas-buscar", columnas);
