"use strict";
var columnas = [
 

    {
        field: "siglas",
        title: "Siglas"
    },
    {
        field: "nombre_tipo",
        title: "Nombre Tipo"
    },
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-tipo-estado", "/tipoestado/default/lista", "#tabla-tipo-estado-buscar", columnas);
