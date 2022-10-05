 
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


var datatable = iniciarTabla("#tabla-tarifas", "/liquidacion/default/lista", "#tabla-tarifas-buscar", columnas);

 
if(datatable !=null){
    console.log('LLENO');
   var result = document.getElementById('LE_OtherCostsDetails_fecha_liquidacion').value;
    console.log( result);

}

$("#fecha_liquidacion").change(function () {
    console.log($("#fecha_liquidacion").val());
    buscarLiquidado()


});

function buscarLiquidado() {


    datatableLiquidado.search(  $("#idEntidad").val() + "/" +$("#fecha_liquidacion").val().toLowerCase(), "mes")
    TotalesLiquidado()

}


var columnasli = [
    {
        field: "fecha",
        title: "fecha",
        width: 50
    },
    {
        field: "numero_guia",
        title: "Guia Pegaso",
        width: 79
    },
    {
        field: "guia_cliente",
        title: "Guia Cliente",
        width: 79
    },

    {
        field: "origen",
        title: "origen",
        width: 79
    },
    {
        field: "destino",
        title: "destino",
        width: 79
    },

    {
        field: "estado",
        title: "estado",
        width: 80
    },
    {
        field: "TARIFA_PROVINCIA",
        title: "TARf PROV.",
        width: 80
    },
    {
        field: "bultos",
        title: "Bultos",
        width: 55
    },
    {
        field: "peso",
        title: "Peso",
        width: 50
    }, {
        field: "DESCD",
        title: "DESCD",
        width: 65
    },
    {
        field: "VIA",
        title: "VIA",
        width: 79
    }, {
        field: "tarifa_base",
        title: "TARIFA BASE",
        width: 79
    },

    {
        field: "peso_exceso",
        title: "PESO EXCESO",
        width: 65
    },
    {
        field: "reembarque",
        title: "reembar.",
        width: 80
    },
    {
        field: "costo",
        title: "costo",
        width: 80
    }
];
var datatableLiquidado = iniciarTabla("#tabla-liquidado", "/liquidacion/default/listar-mes-liquidado", "#tabla-liquidado-buscar", columnasli);



 