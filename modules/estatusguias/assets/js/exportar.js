 function funcionDescargarReporte() {
     if($("#cliente").val()== null){
         alert('Seleccione dato')
     }else{
         $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString();
   //var razon_social, id_remitente, fecha, id_vehiculo, serie;
           
   var data = new FormData();
 
    data.append('estado', $("#estado").val());
    data.append('fechaInicio', $("#fechaInicio").val());
    data.append('fecha_fin', $("#fecha_fin").val());
    data.append('cliente', $("#cliente").val());  

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/estatusguias/default/exportar', true);
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
     };
    
}
 