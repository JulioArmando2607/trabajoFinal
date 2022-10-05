"use strict";
var columnas = [
     {
        field: "nm_solicitud",
        title: "Nuemro Solicitud",
     
    },
    {
        field: "fecha",
        title: "Fecha",
     
    },
    
    {
        field: "hora_recojo",
        title: "Hora Recojo",
      
    },
     
    {
        field: "tipo_servicios",
        title: "Tipo Servicio",
     width: 120
    },
      {
        field: "nombre_estado",
        title: "Estado",
      
    },
    
    {
        field: "accion",
        title: "Acciones",
        width: 200
    }
];

var datatable = iniciarTabla("#tabla-pedidosclientes", "/pedidosclientes/default/lista", "#tabla-pedidosclientes-buscar", columnas);
