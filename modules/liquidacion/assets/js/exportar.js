 function funcionDescargarMesLiquidado() {
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString();

           
   var data = new FormData();

     data.append('idEntidad',$("#idEntidad").val());
    data.append('fecha_liquidacion', $("#fecha_liquidacion").val());


    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/liquidacion/default/export-mes-liquidado', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Liquidacion " + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

   xhr.send(data);
}
 