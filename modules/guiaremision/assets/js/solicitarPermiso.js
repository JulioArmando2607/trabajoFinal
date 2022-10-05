function funcionSoicitarPermiso(id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "¡Quiere Solicitar Permiso para cambiar estado de la Guia?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡Solicitar!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/guiaremision/default/solicitar-permiso',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_guia: id
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Solicitado!", "El cambio de Estado.", "success")
                    }
                 datatableGuia.reload()
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "No se solicito Permiso.", "error")
        }
    });
}
