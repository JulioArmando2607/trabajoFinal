var columnas = [

    {
        field: "nombre_provincia",
        title: "PROVINCIA",
         width: 100
    }, {

        field: "tarifa_m_t_cg",
        title: "TARIFA GENERAL",
        width: 100
    },
    {
        field: "tarifa_m_t_ref",
        title: "REFRIGERADAS",
        width: 100
    },
 
    {
        field: "tarifa_m_t_dm",
        title: "DIFICIL MANEJO",
        width: 100
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

var datatablept = iniciarTabla("#tabla-provincias-terrestre", "/tarifas/default/lista-provincia-t-tarifa", "#tabla-provincias-terrestre-buscar", columnas);

datatablept.search($("#entidad_edit").val(), "entidad")


$("#entidades").change(function () {
    datatablept.search($(this).val().toLowerCase(), "entidad")
});






 