$("#cliente").select2({
    placeholder: "Seleccioné Cliente"
});

var columnas = [

    {
        field: "fecha_salida",
        title: "FECHA SALIDA",
        width: 70
    }, {
        field: "guia_rem_pegaso",
        title: "GUIA REM PEGASO"
    }, {
        field: "guia_rem_tecnocom",
        title: "GUIA REM TECNOCOM"
    },
    {
        field: "bulto",
        title: "BULTO",
        width: 50
    },
    {
        field: "peso",
        title: "PESO",  width: 50
    },

    {
        field: "destino",
        title: "DESTINO"
    },
    {
        field: "consigando",
        title: "CONSIGNADO"
    },
    {
        field: "tipo_envio",
        title: "TIPO ENVIO"
    },

    {
        field: "emp_transporte",
        title: "EMP TRANSPORTES",
        width: 70
    },
    {
        field: "n_factura",
        title: "N FACTURA"
    },  {
        field: "guia_transportista",
        title: "GUIA TRANSPORTISTA"
    }, {
        field: "fecha_entrega",
        title: "FECHA ENTREGA"
    }

];


var datatable = iniciarTabla("#tabla-reportetecnocom", "/reportetecnocom/default/lista", "#tabla-reportetecnocom-buscar", columnas);

function listaresportes() {

    datatable.search($("#fechaInicio").val() + "/" + $("#fecha_fin").val() + "/"+ $("#via_envio").val() + "/" + $("#estado").val().toLowerCase(), "fechaInicio");

}