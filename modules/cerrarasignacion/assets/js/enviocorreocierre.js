function funcionEnviarCorreo(id_atencion_pedidos, nm_solicitud) {
//    $("#loader").show();


    Swal.fire({
        title: "¿Está seguro Cerrar Solicitud?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡Cerrar!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        /* var selected = [];
         $(":checkbox[name=page]").each(function () {
             if (this.checked) {
                 // agregas cada elemento.
                 selected.push($(this).val());
             }
         });*/
        if (result.value) {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/CerrarAsignacion/default/mail',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_atencion_pedidos: id_atencion_pedidos,
                    nm_solicitud: nm_solicitud
                },
                success: function (response) {
                    if (response == true) {
                        Swal.fire("Procesado!", "El registro fue procesado correctamente.", "success")
                        datatableGuia.reload();
                    } else if (response == 0) {

                        Swal.fire("NO cuenta con Guias generadas!", "El registro no fue procesado correctamente.", "error")
                    }
                    //    alert('datos enviados');
                    // Swal.fire("Procesado!", "El registro fue procesado correctamente.", "success")
                    //datatableGuia.reload();
                    /*
                                            $("#pendguias").html(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    dataType: 'json',
                                                    url: APP_URL + '/cerrarasignacion/default/listar-pend-guias',
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    data: {

                                                    },
                                                    success: function (response) {
                                                        $("#pendguias").html(response);


                                                    }
                                                });
                                            });

                                            $("#ultimaguia").html(function () {

                                                $.ajax({
                                                    type: "POST",
                                                    dataType: 'json',
                                                    url: APP_URL + '/procesarguiastotal/default/ultima-guia',
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    data: {

                                                    },
                                                    success: function (response) {
                                                        $("#ultimaguia").html(response);


                                                    }
                                                });
                                            });*/

                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tus registros no seran procesados.", "error")
        }
    });

    // xhr.send(data);
}
