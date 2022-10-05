"use strict";
/**
 *Funcion para activar el loader antes de cargar la vista
 */
window.onbeforeunload = function () {
    $.showLoading();
}

/**
 * Funcion para validar la carga de pagina para el loader
 */
window.onload = function () {
    $.hideLoading();
}

var columnas = [
 
    {
        field: "razon_social",
        title: "Entidad"
    },
    {
        field: "documento",
        title: "Tipo Documento"
    },
    {
        field: "numero_documento",
        title: "Numero Documento"
    },

  
  
  
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatable = iniciarTabla("#tabla-entidades", "/entidades/default/lista", "#tabla-entidades-buscar", columnas);

function funcionDescargarEntidades(){
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();
    data.append('razon_social','');


    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/entidades/default/exportar-entidades', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "entidades-" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

    xhr.send(data);
}
