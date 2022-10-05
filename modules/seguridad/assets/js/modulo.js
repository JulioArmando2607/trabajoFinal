"use strict";
var columnasModulo = [
    {
        field: "nombre",
        title: "Nombre"
    },
    {
        field: "ruta",
        title: "Ruta"
    },
    {
        field: "padre",
        title: "Modulo Padre"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];

var datatableModulo = iniciarTabla("#tabla-modulo", "/seguridad/default/lista", "#tabla-modulo-buscar", columnasModulo);

$("#modal-modulo").on("click", function () {
    $.post(APP_URL + '/seguridad/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Módulo</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-modulo").validate({
                    rules: {
                        nombre: "required",
                        ruta: "required",
                    },
                    messages: {
                        nombre: "Por favor ingrese datos",
                        ruta: "Por favor ingrese datos",
                    },
                    submitHandler: function () {
                        var nombre = $("#nombre").val();
                        var ruta = $("#ruta").val();
                        var padre = $("#padre").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/seguridad/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                nombre: nombre,
                                ruta: ruta,
                                padre: padre,
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatableModulo.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
});


function funcionEditarModulo(id) {
    $.post(APP_URL + '/seguridad/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Módulo</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-modulo").validate({
                    rules: {
                        nombre: "required",
                        ruta: "required",
                    },
                    messages: {
                        nombre: "Por favor ingrese datos",
                        ruta: "Por favor ingrese datos",
                    },
                    submitHandler: function () {
                        var nombre = $("#nombre").val();
                        var ruta = $("#ruta").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/seguridad/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id_modulo: id,
                                nombre: nombre,
                                ruta: ruta,
                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatableModulo.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}


function funcionEliminarModulo(id) {
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
                url: APP_URL + '/seguridad/default/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_modulo: id
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success")
                    }
                    datatableModulo.reload()
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
