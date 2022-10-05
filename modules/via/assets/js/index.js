"use strict";
var columnas = [
   
 
    {
        field: "nombre_via",
        title: "Via"
    },
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-via", "/via/default/lista", "#tabla-via-buscar", columnas);
