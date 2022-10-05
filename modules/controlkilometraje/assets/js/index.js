"use strict";
var columnas = [

    {
        field: "vehiculo",
        title: "vehiculo"
    },
  {
        field: "hora_salida",
        title: "hora salida"
    },

    {
        field: "hora_llegada",
        title: "hora llegada"
    },

    {
        field: "kilometraje_salida",
        title: "kilometraje salida"
    },

    {
        field: "kilometraje_llegada",
        title: "kilometraje llegada"
    },

    {
        field: "kilometro_recorrido",
        title: "kilometro recorrido"
    },

    {
        field: "lugar_destino",
        title: "lugar destino"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 130
    }


];

var datatable = iniciarTabla("#tabla-Control-Kilometraje", "/controlkilometraje/default/lista", "#tabla-Control-Kilometraje-buscar", columnas);
