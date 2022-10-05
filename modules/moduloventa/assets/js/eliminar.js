function funcionEliminarVenta(id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡procesar!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/moduloventa/default/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_venta: id
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Procesado!", "La venta fue cancelada correctamente.", "success")
                    }
                    datatableVenta.reload()
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
