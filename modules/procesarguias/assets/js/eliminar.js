function funcionEliminarGuia(id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡bórralo!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/procesarguias/default/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_guia: id
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success")
                    }
                    datatableGuia.reload();
                    
                        $("#solicitud_pendiente_guia").html(function () {

                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/procesarguias/default/listar-pend-guias',
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                data: {

                                },
                                success: function (response) {
                                    $("#solicitud_pendiente_guia").html(response);


                                }
                            });
                        });

                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
