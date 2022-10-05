 function funcionDescargarReporteTecnocom() {

         $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString();
           
     var data = new FormData();
 
    data.append('estado', $("#estado").val());
    data.append('fechaInicio', $("#fechaInicio").val());
    data.append('fecha_fin', $("#fecha_fin").val());
    data.append('via', $("#via_envio").val());

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/reportetecnocom/default/exportar', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Reporte-Guias" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

   xhr.send(data);

    
}
 