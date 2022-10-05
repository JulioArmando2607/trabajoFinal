"use strict";
var columnas = [
   
    {
        field: "nombre_departamento",
        title: "Nombre Departamento"
    },
   
     {
        field: "nombre_provincia",
        title: "nombre_provincia"
    }, {
        field: "nombre_distrito",
        title: "nombre_distrito"
    },
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-ubigeos", "/ubigeos/default/lista", "#tabla-ubigeos-buscar", columnas);
