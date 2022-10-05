/*function funcionDescargarExcel() {
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();
    data.append('razon_social',razon_social);
    data.append('id_remitente', id_remitente);
    data.append('fecha', fecha);
    data.append('id_vehiculo', id_vehiculo);
    data.append('serie', serie); 

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/guiaremision/default/exportar', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "reporte-manifiesto_" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

  //  xhr.send(data);
}
*/