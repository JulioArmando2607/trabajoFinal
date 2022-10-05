"use strict";

/**
 *Funcion para activar el loader antes de cargar la vista
 */
window.onbeforeunload = function () {
    $.showLoading();
}

/**
 * Funcion para validar la carga de pagina para el loader
 */
window.onload = function () {
    $.hideLoading();
}

var columnas = [
     {
        field: "nm_solicitud",
        title: "Nuemro Solicitud",
         width: 150 
     
    },
    {
        field: "fecha",
        title: "Fecha",
          width: 100
     
    },
    
    {
        field: "hora_recojo",
        title: "Hora Recojo",
  width: 75      
    },
     
       {
        field: "cliente",
        title: "Cliente",
      
    },
    {
        field: "tipo_servicios",
        title: "Tipo Servicio",
     width: 120
    },
      {
        field: "nombre_estado",
        title: "Estado",
         width: 75   
      
    },
    
    {
        field: "accion",
        title: "Acciones",
        width: 100
    }
];

var datatable = iniciarTabla("#tabla-pedidoclienteop", "/pedidoclienteop/default/lista", "#tabla-pedidoclienteop-buscar", columnas);
