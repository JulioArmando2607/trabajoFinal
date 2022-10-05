var detalle_guia = [];
var detalle_guia_rc = [];

$(document).ready(function () {

    $("#remitente").select2({
        placeholder: "Seleccioné Remitente"
    });

    $("#destinatario").select2({
        placeholder: "Seleccioné Destinatario"
    });

    $("#cliente").select2({
        placeholder: "Seleccioné Cliente"
    });

    $("#agente").select2({
        placeholder: "Seleccioné Agente"
    });

    $("#conductor").select2({
        placeholder: "Seleccioné Conductor"
    });

  $("#auxiliar").select2({
        placeholder: "Seleccioné Auxiliar"
    });
    
    $("#vehiculo").select2({
        placeholder: "Seleccioné Vehiculo"
    });

    $("#direccion_partida").select2({
        placeholder: "Seleccioné Direccion Partida"
    });
    
    $("#area").select2({
        placeholder: "Seleccioné Area"
    });
    $("#distrito").select2({
        placeholder: "Seleccioné Distrito"
    });
    $("#direccion_llegada").select2({
        placeholder: "Seleccioné Direccion Llegada"
    });
 

 
    $("#btn-guardar").click(function () {
        $("#frm-pedidosclientes").validate({
            rules: {

                fecha: "required",
                hora: "required",
                tipo_servicio: "required",
                distrito: "required",
                direccion: "required",
                contacto: "required",
                area: "required",
              //  referencia: "required",
                telefono: "required",
                cantidad_personas: "required",
                tipo_unidad: "required",
                stoka: "required",
                fragil: "required",
        

            },
            messages: {
                fecha: "Ingrese datos",
                hora: "Ingrese datos",
                tipo_servicio: "Ingrese datos",
                distrito: "Seleccioné",
                direccion: "Ingrese datos",
                contacto: "Ingrese datos",
                area: "Seleccioné",
              //  referencia: "Seleccioné",
                telefono: "Seleccioné",
                cantidad_personas: "Seleccioné",
                tipo_unidad: "Seleccioné",
                stoka: "Seleccioné",
                fragil: "Seleccioné",
         
            },
            submitHandler: function () {
                var fecha = $("#fecha").val();
                var hora = $("#hora").val();
                var tipo_servicio = $("#tipo_servicio").val();
                var distrito = $("#distrito").val();
                var direccion = $("#direccion").val();
                var contacto = $("#contacto").val();
                var area = $("#area").val();
                var referencia = $("#referencia").val();
                var telefono = $("#telefono").val();
                var cantidad_personas = $("#cantidad_personas").val();
                var tipo_unidad = $("#tipo_unidad").val();
                var stoka = $("#stoka").val();
                var fragil = $("#fragil").val();
                var cantidad = $("#cantidad").val();
                var peso = $("#peso").val();
                var alto = $("#alto").val();
                var ancho = $("#ancho").val();
                var largo = $("#largo").val();
                var esta_listo = $("#esta_listo").val();
                var observacion = $("#observacion").val(); 
         

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/pedidosclientes/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {

                        fecha: fecha,
                        hora: hora,
                        tipo_servicio: tipo_servicio,
                        distrito: distrito,
                        direccion: direccion,
                        contacto: contacto,
                        area: area,
                        referencia: referencia,
                        telefono: telefono,
                        cantidad_personas: cantidad_personas,
                        tipo_unidad: tipo_unidad,
                        stoka: stoka,
                        fragil: fragil,
                        cantidad: cantidad,
                        peso: peso,
                        alto: alto,
                        ancho: ancho,
                        largo: largo,
                        esta_listo: esta_listo,
                        observacion:observacion,
                        

                    },
                    success: function (response) {
                        window.location.href = APP_URL + '/pedidosclientes';
                    }
                });
            }
        });
    });
});
 