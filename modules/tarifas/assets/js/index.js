 
var columnas = [

    {
        field: "razon_social",
        title: "Entidad"
    }, {
        field: "numero_documento",
        title: "Numero Documento"
    },
   

    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];


var datatable = iniciarTabla("#tabla-tarifas", "/tarifas/default/lista", "#tabla-tarifas-buscar", columnas);
