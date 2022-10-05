function funcionDescargarExcel(razon_social, fecha,serie) {
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();
    data.append('razon_social',razon_social);
    data.append('fecha', fecha);    
    data.append('serie', serie);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/manifiestoventa/default/desarrollo', true);
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

    xhr.send(data);
}

function funcionDescargarCierre(razon_social, fecha,serie) {
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();
    data.append('razon_social',razon_social);
    data.append('fecha', fecha);    
    data.append('serie', serie);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/manifiestoventa/default/cierre', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "reporte-cierre_" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

    xhr.send(data);
}
