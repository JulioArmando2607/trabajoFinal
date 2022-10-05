"use strict";
var columnas = [
    {
        field: "licencia",
        title: "Licencia",
       // width: 75
    },
    {
        field: "nombres",
        title: "Conductor"
    },
     {
        field: "apellido_paterno",
        title: "Apellidos"
    },
  
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-conductores", "/conductores/default/lista", "#tabla-conductores-buscar", columnas);
