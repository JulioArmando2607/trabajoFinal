

var columnas = [

    {
        field: "vehiculo",
        title: "vehiculo",

    }, {
        field: "suma_total_kl",
        title: "suma total kl"
    }, {
        field: "alertaKilometraje",
        title: "Afinamiento"
    }

];


var datatable = iniciarTabla("#tabla-reportekilometraje", "/reportekilometraje/default/lista", "#tabla-reportekilometraje-buscar", columnas);

function listaresportes() {

    datatable.search($("#fechaInicio").val() + "/" + $("#fecha_fin").val() + "/" + $("#cliente").val() + "/" + $("#estado").val().toLowerCase(), "fechaInicio");

}