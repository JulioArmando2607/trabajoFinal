$("#cliente").select2({
    placeholder: "Seleccioné Cliente"
});

var columnas = [

    {
        field: "fecha_emision",
        title: "fech. emision",
        width: 70
    }, {
        field: "numero_guia",
        title: "numero guia"
    }, {
        field: "cliente_origen",
        title: "cliente origen"
    },
    {
        field: "direccion_origen",
        title: "direc. origen"
    },
    {
        field: "ciudad_origen",
        title: "distrito/depart origen"
    },

    {
        field: "cliente_destino",
        title: "cliente destino"
    },
    {
        field: "direccion_destino",
        title: "direc. destino"
    },
    {
        field: "ciudad_destino",
        title: "distrito/depart destino"
    },

    {
        field: "via",
        title: "via",
        width: 70
    },
    {
        field: "tipo_servicio",
        title: "tipo servicio"
    },
    {
        field: "estado",
        title: "estado"
    },
];


var datatable = iniciarTabla("#tabla-reportegrem", "/reporteguiasrem/default/lista", "#tabla-reportegrem-buscar", columnas);

function listaresportes() {

    datatable.search($("#fechaInicio").val() + "/" + $("#fecha_fin").val() + "/" + $("#cliente").val() + "/" + $("#estado").val().toLowerCase(), "fechaInicio");

}