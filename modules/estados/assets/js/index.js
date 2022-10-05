"use strict";
var columnas = [
    
        {
        field: "nombre_estado",
        title: "Nombre Estado"
    },
    {
        field: "nombre_tipo",
        title: "Tipo Estado"
    },
    {
        field: "siglas",
        title: "Siglas Tipo Estado"
    },
   
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-estados", "/estados/default/lista", "#tabla-estados-buscar", columnas);
