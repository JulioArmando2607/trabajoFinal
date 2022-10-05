"use strict";
var columnas = [

    {
        field: "razon_social",
        title: "Entidad"
    },
    {
        field: "nombre_distrito",
        title: "Distrito"
    },
    {
        field: "direccion",
        title: "direccion"
    },

    {
        field: "urbanizacion",
        title: "urbanizacion"
    },
 
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-direcciones", "/direcciones/default/lista", "#tabla-direcciones-buscar", columnas);


function funcionDescargarDirecciones(){
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();
    data.append('razon_social','');


    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/direcciones/default/exportar-direcciones', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Direcciones_" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

    xhr.send(data);
}
