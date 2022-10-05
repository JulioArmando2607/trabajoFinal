"use strict";
var columnas = [
  
    {
        field: "cod_producto",
        title: "Cod Producto",
   
    },
    {
        field: "nombre_producto",
        title: "Nombre Producto"
    },
    {
        field: "unidad_medida",
        title: "Unidad Medida"
    },
    
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-productos", "/productos/default/lista", "#tabla-productos-buscar", columnas);
