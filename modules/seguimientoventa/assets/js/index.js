"use strict";
var columnas = [
 
    {
        field: "numero_guia",
        title: "número",
        width: 80
    },
    {
        field: "fecha",
        title: "fecha",
        width: 80
    },
     
      {
        field: "forma_pago",
        title: "Forma Pago",
        width: 80
    },
    {
        field: "nombre_estado",
        title: "Estado",
        width: 80
    },
    
    
      {
        field: "estado_venta",
        title: "Estado Venta",
        width: 80
    },
    {
        field: "tipo_entrega",
        title: "Tipo Entrega",
        width: 80
    },
    
    
    
    {
        field: "accion",
        title: "Acciones",
        width: 130
    }
];

var datatable = iniciarTabla("#tabla-seguimientoventa", "/seguimientoventa/default/lista", "#tabla-seguimientoventa-buscar", columnas);
