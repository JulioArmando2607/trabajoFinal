"use strict";
var columnas = [
   
 
    {
        field: "fecha",
        title: "Fecha Manifiesto"
    },
    
    
    {
        field: "razon_social",
        title: "Razon Social"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 80
    }
];

var datatable = iniciarTabla("#tabla-manifiestoventa", "/manifiestoventa/default/lista", "#tabla-manifiestoventa-buscar", columnas);
