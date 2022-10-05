
"use strict";
var columnas = [
    {
        field: "fecha",
        title: "fecha",
        width: 65
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
        field: "TARIFA_PROVINCIA",
        title: "TARIFA PROV.",
        width: 80
    },
    {
        field: "bultos",
        title: "Bultos",
        width: 79
    },
    {
        field: "peso",
        title: "Peso",
        width: 79
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
        field: "TARIFA_BASE",
        title: "TARIFA BASE",
        width: 79
    },

    {
        field: "PESO_EXCESO",
        title: "PESO EXCESO",
        width: 80
    },
    {
        field: "REEMBARQ.",
        title: "REEMBARQ.",
        width: 80
    },
    {
        field: "costo",
        title: "costo",
        width: 80
    },

    {
        field: "estado",
        title: "estado",
        width: 80
    }
];

var datatableGuias = iniciarTabla("#tabla-liquidacion", "/liquidacion/default/listas", "#tabla-liquidacion-buscar", columnas);

var columnass = [
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
    },
    {
        field: "accion",
        title: "Acciones",
        width: 100
    }
];
var datatableGuiass = iniciarTabla("#tabla-liquidacions", "/liquidacion/default/listas-liquidar", "#tabla-liquidacions-buscar", columnass);
 
function listar() {


    datatableGuias.search($("#fechaInicio").val() + "/" + $("#fecha_fin").val() + "/" + $("#idEntidad").val().toLowerCase(), "fechaInicio")
    TotalesLiquidacion()

}

function TotalesLiquidacion() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/liquidacion/default/calcular',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            fechaInicio: $("#fechaInicio").val(),
            fecha_fin: $("#fecha_fin").val(),
            idEntidad: $("#idEntidad").val(),
        },
        success: function (response) {
            console.log(response['igv']);

            $("#totalliquiini").html(response['total']);
            $("#subtotaliquiini").html(response['totalsuma']);
            $("#igvliquiini").html(response['igv']);

        }
    });
}
$(document).ready(function () {
    $("#btn-liqui").click(function () {
        alert()

    });
});

function  funcionLiquidar() {

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/liquidacion/default/liquidar',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

    });
}