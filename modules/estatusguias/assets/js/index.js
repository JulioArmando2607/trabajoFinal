$("#cliente").select2({
    placeholder: "Seleccioné Cliente"
});

var columnas = [

    {
        field: "fecha_emision",
        title: "fech. emision",
        width: 100

    }, {
        field: "numero_guia",
        title: "numero guia",
        width: 100
    }, {
        field: "cliente_origen",
        title: "cliente origen"
    },
    {
        field: "cliente_destino",
        title: "cliente destino",
        width: 200
    },

    {
        field: "ciudad_destino",
        title: "distrito/depart destino",
        width: 150
    },

    {
        field: "via",
        title: "via",
        width: 70
    },

    {
        field: "estado",
        title: "estado"
    },
];


var datatableestatus = iniciarTabla("#tabla-estatusguias", "/estatusguias/default/lista", "#tabla-estatusguias-buscar", columnas);

function listaresportes() {

    datatableestatus.search($("#fechaInicio").val() + "/" + $("#fecha_fin").val() + "/" + $("#cliente").val() + "/" + $("#estado").val().toLowerCase(), "fechaInicio");

}