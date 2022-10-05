"use strict";
var columnas = [
   
 
    {
        field: "serie",
        title: "Serie"
    },
   
   
    {
        field: "correlativo",
        title: "Correlativo"
    },
      {
        field: "guia_venta",
        title: "Guia Venta"
    },
     {
        field: "tipo_comprobante",
        title: "Tipo_comprobante"
    },
       {
        field: "cliente",
        title: "cliente"
    },
       {
        field: "numero_documento",
        title: "numero_documento"
    },
    
         {
        field: "total_monto",
        title: "total"
    },
   
       {
        field: "estado",
        title: "Estado Factura"
    },
       {
        field: "fecha_reg",
        title: "Fecha Registro"
    },
    
   //
   
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-facturas", "/facturas/default/lista", "#tabla-facturas-buscar", columnas);
