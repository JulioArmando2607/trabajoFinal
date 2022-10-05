"use strict";
var columnas = [

    {
        field: "nombre_marca",
        title: "Nombre Marca"
    },

    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-marca-vehiculo", "/marcavehiculo/default/lista", "#tabla-marca-vehiculo-buscar", columnas);
