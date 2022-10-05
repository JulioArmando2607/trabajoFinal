"use strict";
var columnas = [
 
    {
        field: "razon_social",
        title: "Transportista",
        width: 200
    },
    {
        field: "documento",
        title: "Tipo Documento",
        width: 100
    },
    {
        field: "numero_documento",
        title: "Numero Documento"
    },
   {
        field: "direccion",
        title: "Direccion"
    },
  
  {
        field: "nombre_distrito",
        title: "Distrito"
    },
    
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-transportista", "/transportista/default/lista", "#tabla-transportista-buscar", columnas);
