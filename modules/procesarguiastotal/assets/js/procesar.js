function funcionProcesarPend() {
//    $("#loader").show();


    Swal.fire({
        title: "¿Está seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡Procesa!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        var selected = [];
        $(":checkbox[name=page]").each(function () {
            if (this.checked) {
                // agregas cada elemento.
                selected.push($(this).val());
            }
        });
        if (result.value) {

            if (selected.length) {
                var valParam = JSON.stringify(selected);
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/procesarguiastotal/default/procesar',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {selected: valParam},
                    success: function (response) {
                        //    alert('datos enviados');
                        Swal.fire("Procesado!", "El registro fue procesado correctamente.", "success")
                        datatableGuia.reload();

                            $("#pendguias").html(function () {

                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/procesarguiastotal/default/listar-pend-guias',
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
                        });
 
                    }});
            } else
                notificacion('Debes seleccionar al menos una opción.', 'warning');
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tus registros no seran procesados.", "error")
        }
    });

    // xhr.send(data);
}

function funcionProcesar() {

    // defines un arreglo
    var selected = [];
    $(":checkbox[name=page]").each(function () {
        if (this.checked) {
            // agregas cada elemento.
            selected.push($(this).val());
        }
    });
    if (selected.length) {
        var valParam = JSON.stringify(selected);
        $.ajax({
            // cache: false,
            type: 'post',
            dataType: 'json', // importante para que 
            data: {selected: valParam}, // jQuery convierta el array a JSON
            url: APP_URL + '/procesarguiastotal/default/procesar',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (data) {
                alert('datos enviados');
            }
        });

        // esto es solo para demostrar el json,
        // con fines didacticos
        alert(JSON.stringify(selected));

    } else
        alert('Debes seleccionar al menos una opción.');

    return false;

}
$(document).ready(function () {
    $('#enviar').click(function () {
        // defines un arreglo
        var selected = [];
        $(":checkbox[name=page]").each(function () {
            if (this.checked) {
                // agregas cada elemento.
                selected.push($(this).val());
            }
        });
        if (selected.length) {

            $.ajax({
                cache: false,
                type: 'post',
                dataType: 'json', // importante para que 
                data: selected, // jQuery convierta el array a JSON
                url: 'roles/paginas',
                success: function (data) {
                    alert('datos enviados');
                }
            });

            // esto es solo para demostrar el json,
            // con fines didacticos
            alert(JSON.stringify(selected));

        } else
            alert('Debes seleccionar al menos una opción.');

        return false;
    });
});