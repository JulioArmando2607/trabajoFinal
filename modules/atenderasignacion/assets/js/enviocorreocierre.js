function funcionEnviarCorreo(id_atencion_pedidos, nm_solicitud) {
     Swal.fire({
        title: "¿Está seguro Cerrar Solicitud?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡Cerrar!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {

        if (result.value) {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/atenderasignacion/default/mail',
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

                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tus registros no seran procesados.", "error")
        }
    });

}
