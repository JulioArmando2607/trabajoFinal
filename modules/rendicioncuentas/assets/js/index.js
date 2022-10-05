"use strict";
/*$("#abono_cuenta_de").select2({
    placeholder: "Seleccioné"
});$("#rinde").select2({
    placeholder: "Seleccioné"
});**/
var columnas = [

    {
        field: "fecha",
        title: "fecha"
    },
    {
        field: "nr_operacion",
        title: "Nr operacion"
    },
    {
        field: "abono_cuenta_de",
        title: "abono cuenta de"
    },
    {
        field: "rinde",
        title: "rinde"
    },

    {
        field: "importe_entregado",
        title: "importe entregado"
    },
    {
        field: "diferencia_depositar_reembolsar",
        title: "diferencia depositar reembolsar"
    },
    {
        field: "total_gasto",
        title: "total gasto"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatableRendicioncuentas = iniciarTabla("#tabla-rendicioncuentas", "/rendicioncuentas/default/lista", "#tabla-rendicioncuentas-buscar", columnas);
