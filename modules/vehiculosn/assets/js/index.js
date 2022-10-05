"use strict";
var columnas = [
/*  "marca" => $row['marca'],
                "version" => $row['version'],
                "modelo" => $row['modelo'],
                "matricula" => $row['matricula'],*/
    {
        field: "marca",
        title: "marca"
    },
    {
        field: "version",
        title: "version"
    },
    {
        field: "modelo",
        title: "modelo"
    },

    {
        field: "matricula",
        title: "matricula"
    },
 
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-vehiculosn", "/vehiculosn/default/lista", "#tabla-vehiculosn-buscar", columnas);
