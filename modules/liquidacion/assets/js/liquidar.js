/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    TotalesLiquidacions()
    $("#button-liquida").click(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/liquidacion/default/liquidacion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                idEntidad: $("#idEntidad").val(),
                fechaInicio: $("#fechaInicio").val(),
                fecha_fin: $("#fecha_fin").val(),
            },

            success: function (response) {
                console.log(response);
                 //window.(APP_URL+'/liquidacion/default/liquidar');
                  window.location.href = APP_URL+'/liquidacion/default/liquidar/'+$("#idEntidad").val()+'?fecha='+$("#fechaInicio").val();
                    TotalesLiquidacions()
            }

        });

    });
});

$(document).ready(function () {
    $("#button-liquida-guardar").click(function () {
      $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/liquidacion/default/liquidar-liquidacion',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {

            },

            success: function (response) {
                console.log(response);

                if(response){



                   $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: APP_URL + '/liquidacion/default/mail',
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        data: {
                             idEntidad: $("#idEntidad").val(),
                             fechali: $("#fechali").val(),
                        },

                        success: function (response) {
                            if(response==true){
                                window.location.href = APP_URL + '/liquidacion';
                                console.log(response);
                                datatableGuiass.reload()
                            }

                        }

                    });


                }

            }

        }); 

        console.log('Hola');
    });



});



function TotalesLiquidacions() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/liquidacion/default/calcularli',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
           // fechaInicio: $("#fechaInicio").val(),
          //  fecha_fin: $("#fecha_fin").val(),
             idEntidad: $("#idEntidad").val(),
        },
        success: function (response) {
            console.log(response['igv']);

            $("#totalliqui").html(response['total']);
            $("#subtotaliqui").html(response['totalsuma']);
            $("#igvliqui").html(response['igv']);

        }
    });
}

function TotalesLiquidado() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: APP_URL + '/liquidacion/default/calcular-mes-li',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            idEntidad: $("#idEntidad").val(),
            fecha_liquidacion: $("#fecha_liquidacion").val()

        },
        success: function (response) {
            console.log(response['igv']);

            $("#totalliqui").html(response['total']);
            $("#subtotaliqui").html(response['totalsuma']);
            $("#igvliqui").html(response['igv']);

        }
    });
}




