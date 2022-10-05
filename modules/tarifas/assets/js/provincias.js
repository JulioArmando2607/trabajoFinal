
var columnas = [

    {
        field: "nombre_provincia",
        title: "PROVINCIA-DISTRITO",
          width: 100
    }, {
         
        field: "tarifa_m_a_cg",
        title: "TARIFA GENERAL",
                width: 80
    },
     {
        field: "tarifa_m_a_vr",
        title: "VACUNAS REFRIGERADAS",
                width: 90
    },
     {
        field: "tarifa_m_a_pd",
        title: "MERCANCIA PELIGROSA",
                width: 90
    },
        {
        field: "tarifa_m_a_dm",
        title: "DIFICIL MANEJO",
                width: 90
    },
    {
        field: "razon_social",
        title: "Entidad"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-provincias", "/tarifas/default/lista-provincia-tarifa", "#tabla-provincias-buscar", columnas);
datatable.search($("#entidad_edit").val(), "entidad")

$("#entidades").change(function () {
    datatable.search($(this).val().toLowerCase(), "entidad")
});

 