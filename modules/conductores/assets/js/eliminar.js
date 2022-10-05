function funcionEliminar(id, id_persona) {
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
                url: APP_URL + '/conductores/default/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_empleado: id,
                    id_persona: id_persona,
                    conductor:0

                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success")
                    }
                    datatable.reload()
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
