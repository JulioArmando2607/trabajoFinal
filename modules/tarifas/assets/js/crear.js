/*function funcionAlertaCrearTraifa() {
 window.location.href = "tarifas/default/crear";
 //   Swal.fire({
 /*        title: "¿Que Tipo de Tarifa?",
 text: "¡Quieres crear!",
 icon: "warning",
 showCancelButton: true,
 confirmButtonText: "¡Terrestre!",
 cancelButtonText: "¡Aereo!",
 
 reverseButtons: true
 }).then(function (result) {
 if (result.value) {
 
 
 } else if (result.dismiss === "cancel") {
 window.location.href = "tarifas/default/crear-aereo";
 }
 }); 
 }
 $("#divtabs").hide();
 
 $("#entidades").select2({
 placeholder: "Seleccioné Cliente"
 });
 
 $("#entidades").change(function () {
 if (($(this).val()) > 0) {
 $("#divtabs").show();
 $("#id_entidad").val($(this).val());
 
 } else {
 $("#divtabs").hide();
 
 }
 
 
 });
 
 var id_e= $("#entidades").val();
 
 
 
 
 function funcioncrearprov() {
 
 $.post(APP_URL + '/tarifas/default/get-modal', {}, function (resp) {
 bootbox.dialog({
 title: "<h2><strong>Registro </strong></h2>",
 message: resp.plantilla,
 buttons: {}
 });
 
 $("#provincia").select2({
 placeholder: "Seleccioné provincia"
 });
 $("#btn-cancelar").click(function () {
 bootbox.hideAll();
 });
 
 
 $("#id_entidad").val( $("#entidades").val());
 
 $(document).ready(function () {
 $("#btn-guardar-pr").click(function () {
 $("#form-tarifapro").validate({
 rules: {
 //entidad: "required",
 tarifa: "required",
 provincia: "required",
 
 },
 messages: {
 tarifa: "Por favor ingrese datos",
 provincia: "Por favor ingrese datos",
 
 },
 submitHandler: function () {
 
 var tarifa = $("#tarifa").val();
 var provincia = $("#provincia").val();
 var entidad =   $("#id_entidad").val();
 
 $.ajax({
 type: "POST",
 dataType: 'json',
 url: APP_URL + '/tarifas/default/create-p-a',
 contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
 data: {
 tarifa: tarifa,
 provincia: provincia,
 entidad:entidad
 
 },
 success: function (response) {
 bootbox.hideAll();
 if (response) {
 notificacion('Accion realizada con exito', 'success');
 } else {
 notificacion('Error al guardar datos', 'error');
 }
 datatable.reload()
 }
 });
 }
 });
 });
 });
 
 
 }, 'json');
 }
 
 
 $(document).ready(function () {
 $("#crear-provincia").click(function () {
 
 alert('alerta');
 
 });});
 
 
 $(document).ready(function () {
 $("#btn-guardar-ta-a").click(function () {
 
 
 submitHandler: function () {
 
 var tarifa = $("#tarifa").val();
 var provincia = $("#provincia").val();
 var entidad =   $("#id_entidad").val();
 
 $.ajax({
 type: "POST",
 dataType: 'json',
 url: APP_URL + '/tarifas/default/crear-ta',
 contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
 data: {
 tarifa: tarifa,
 provincia: provincia,
 entidad:entidad
 
 },
 success: function (response) {
 alert('HOLASS');
 bootbox.hideAll();
 if (response) {
 notificacion('Accion realizada con exito', 'success');
 } else {
 notificacion('Error al guardar datos', 'error');
 }
 datatable.reload()
 }
 });
 }
 
 });
 });
 
 
 
 */


$("#divtabs").hide();
$("#divview").hide();

$("#entidades").select2({
    placeholder: "Seleccioné Cliente"
});
//$("#tipotarifa").select2({
  //  placeholder: "Seleccioné Tipo"
//});

$("#entidades").change(function () {
    if (($(this).val()) > 0) {
        $("#divtabs").show();
        $("#id_entidad").val($(this).val());
        calcular();
        datatable.reload();
        datatablept.reload();
        $("#divview").show();

    } else {
        $("#divtabs").hide();
        calcular();
        datatable.reload();
        datatablept.reload();
        $("#divview").hide();
    }


});
function  funcionAgregarPro() {
    $.post(APP_URL + '/tarifas/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Provincia</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });
        $("#provincia").select2({
            placeholder: "Seleccioné provincia"
        });
        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
          $("#provincia").select2({
            placeholder: "Seleccioné Provincia"
        });
        $("#id_entidad").val($("#entidades").val());
        $(document).ready(function () {
            $("#btn-guardar-pr").click(function () {
                $("#form-tarifapro").validate({
                    rules: {
                        //entidad: "required",
                        tarifa: "required",
                        provincia: "required",
                       /// tipotarifa:"required",
                    },
                    messages: {
                        tarifa: "Por favor ingrese datos",
                        provincia: "Por favor ingrese datos",
                        /// tipotarifa:"Por favor Seleccione el tipo",
                    },
                    submitHandler: function () {

                        var tarifa = $("#tarifa").val();
                        var provincia = $("#provincia").val();
                        var entidad = $("#id_entidad").val();
                        var vacunas_ref = $("#vacunas_ref").val();
                        var mercancia_pel = $("#mercancia_pel").val();
                        var dificil_manejo = $("#dificil_manejo").val();
                    //   var tipotarifa = $("#tipotarifa").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tarifas/default/create-p-a',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                provincia: provincia,
                                entidad: entidad,
                                tarifa: tarifa,
                                vacunas_ref: vacunas_ref,
                                mercancia_pel: mercancia_pel,
                                dificil_manejo: dificil_manejo, 
                            //    tipotarifa:tipotarifa


                            },
                            success: function (response) {
                                console.log(response);
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload();
                                datatablept.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}

function  funcionEProvincia() {
    $.post(APP_URL + '/tarifas/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Provincia </strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });
        $("#provincia").select2({
            placeholder: "Seleccioné provincia"
        });
        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#id_entidad").val($("#entidad_edit").val());
        $(document).ready(function () {
            $("#btn-guardar-pr").click(function () {
                $("#form-tarifapro").validate({
                    rules: {
                        //entidad: "required",
                        tarifa: "required",
                        provincia: "required",
                    },
                    messages: {
                        tarifa: "Por favor ingrese datos",
                        provincia: "Por favor ingrese datos",
                    },
                    submitHandler: function () {

                        var tarifa = $("#tarifa").val();
                        var provincia = $("#provincia").val();
                        var entidad = $("#id_entidad").val();
                        var vacunas_ref = $("#vacunas_ref").val();
                        var mercancia_pel = $("#mercancia_pel").val();
                        var dificil_manejo = $("#dificil_manejo").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tarifas/default/create-p-a',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                provincia: provincia,
                                entidad: entidad,
                                tarifa: tarifa,
                                vacunas_ref: vacunas_ref,
                                mercancia_pel: mercancia_pel,
                                dificil_manejo: dificil_manejo


                            },
                            success: function (response) {
                                alert()
                                console.log(response)
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload();
                                datatablept.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}
function  funcionAgregarProTerrestre() {
    $.post(APP_URL + '/tarifas/default/get-modal-t', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Provincia </strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });
        $("#provincia_te").select2({
            placeholder: "Seleccioné provincia"
        });
        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });
        $("#id_entidad_te").val($("#entidades").val());
        $(document).ready(function () {
            $("#btn-guardar-prt").click(function () {
                $("#form-tarifaprot").validate({
                    rules: {
 
                        tarifa: "required",
                        provincia: "required",
                    },
                    messages: {
                        tarifa: "Por favor ingrese datos",
                        provincia: "Por favor ingrese datos",
                    },
                    submitHandler: function () {


                        var provincia_te = $("#provincia_te").val();
                        var id_entidad_te = $("#id_entidad_te").val();
                        var tarifa_terrestre = $("#tarifa_terrestre").val();
                        var refrigeradas_te = $("#refrigeradas_te").val();
                         var dificil_manejo_te = $("#dificil_manejo_te").val();
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tarifas/default/create-p-t',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                provincia_te: provincia_te,
                                id_entidad_te: id_entidad_te,
                                tarifa_terrestre: tarifa_terrestre,
                                refrigeradas_te: refrigeradas_te,
                                 dificil_manejo_te: dificil_manejo_te


                            },
                            success: function (response) {
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload()
                                datatablept.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}



function guardartarifaAerea() {

    $.ajax({
        type: "POST",
        dataType: 'json', //GuardarTGA
        url: APP_URL + '/tarifas/default/guardar-t-g-a',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {

            entidad: $("#entidades").val(),
            costo: $("#costo").val(),
            igv: $("#igv").val(),
            total: $("#igv_total_a").val(),
            costo_t_a_ref: $("#costo_t_a_ref").val(),
            igv_t_a_ref: $("#igv_t_a_ref").val(),
            total_t_a_ref: $("#total_t_a_ref").val(),
            costo_t_a_pel: $("#costo_t_a_pel").val(),
            igv_t_a_pel: $("#igv_t_a_pel").val(),
            total_t_a_pel: $("#total_t_a_pel").val(),
        }
        ,
        success: function (response) {
            alert(response);
            /*  bootbox.hideAll();
             if (response) {
             notificacion('Accion realizada con exito', 'success');
             } else {
             notificacion('Error al guardar datos', 'error');
             }
             datatable.reload()*/
        }
    }
    );
    /*    $(document).ready(function () {
     $("#btn-guardar-pr").click(function () {
     $().validate({
     
     submitHandler: function () {
     var costo_aereo_tg =$("#costo").val();
     var igv_tg_ =  $("#igv").val(igv_tg);                   
     var total = $("#igv_total_a").val(total);
     
     
     }
     });
     });
     });
     */

}

function guardartarifaTerrestre() {

    $.ajax({
        type: "POST",
        dataType: 'json', //GuardarTGA
        url: APP_URL + '/tarifas/default/guardar-t-g-t',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {

            entidad: $("#entidades").val(),
            
            costo: $("#costo_t_c_p_d").val(),
            igv: $("#igv_t_c_p_d").val(),
            total: $("#total_t_c_p_d").val(),

            tarifa_m_a_c: $("#costo").val(),
            tarifa_m_a_igv: $("#igv").val(),
            tarifa_m_a_total: $("#igv_total_a").val(),
            costo_t_a_ref: $("#costo_t_a_ref").val(),
            igv_t_a_ref: $("#igv_t_a_ref").val(),
            total_t_a_ref: $("#total_t_a_ref").val(),
            costo_t_a_pel: $("#costo_t_a_pel").val(),
            igv_t_a_pel: $("#igv_t_a_pel").val(),
            total_t_a_pel: $("#total_t_a_pel").val(),
        }
        ,
        success: function (response) {
            alert(response);
            /*  bootbox.hideAll();
             if (response) {
             notificacion('Accion realizada con exito', 'success');
             } else {
             notificacion('Error al guardar datos', 'error');
             }
             datatable.reload()*/
        }
    }
    );
    /*    $(document).ready(function () {
     $("#btn-guardar-pr").click(function () {
     $().validate({
     
     submitHandler: function () {
     var costo_aereo_tg =$("#costo").val();
     var igv_tg_ =  $("#igv").val(igv_tg);                   
     var total = $("#igv_total_a").val(total);
     
     
     }
     });
     });
     });
     */

}


function guardartarifa() {

    $.ajax({
        type: "POST",
        dataType: 'json', //GuardarTGA
        url: APP_URL + '/tarifas/default/crear-tarifa',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {

            entidad: $("#entidades").val(),
            

            costo_t_g_ref: $("#costo_t_g_ref").val(),
            igv_t_g_ref: $("#igv_t_g_ref").val(),
            total_t_g_ref: $("#total_t_g_ref").val(),

            tarifa_m_a_c: $("#costo").val(),
            tarifa_m_a_igv: $("#igv").val(),
            tarifa_m_a_total: $("#igv_total_a").val(),
            
            costo_t_a_ref: $("#costo_t_a_ref").val(),
            igv_t_a_ref: $("#igv_t_a_ref").val(),
            total_t_a_ref: $("#total_t_a_ref").val(),
            
            ////////////////////////////////////////////////
            
            costo_t_a_pel: $("#costo_t_a_pel").val(),
            igv_t_a_pel: $("#igv_t_a_pel").val(),
            total_t_a_pel: $("#total_t_a_pel").val(),
            
            tarifa_peso_a_base_general: $("#tarifa_peso_a_base_general").val(),
            tarifa_peso_a_base_ref: $("#tarifa_peso_a_base_ref").val(),
            tarifa_peso_a_base_pel: $("#tarifa_peso_a_base_pel").val(),
            
            tarifa_peso_t_base_general: $("#tarifa_peso_t_base_general").val(),
            tarifa_peso_t_base_ref: $("#tarifa_peso_t_base_ref").val(),
            tarifa_peso_t_base_pel: $("#tarifa_peso_t_base_pel").val(),
            
            tarifa_m_t_costo: $("#tarifa_m_t_costo").val(),
            tarifa_m_t_igv: $("#tarifa_m_t_igv").val(),
            tarifa_m_t_total: $("#tarifa_m_t_total").val(),
            
            tarifa_m_t_costo_ref: $("#tarifa_m_t_costo_ref").val(),
            igv_t_g_ref: $("#igv_t_g_ref").val(),
            total_t_g_ref: $("#total_t_g_ref").val(),

            costo_t_c_p_d: $("#costo_t_c_p_d").val(),
            igv_t_c_p_d: $("#igv_t_c_p_d").val(),
            total_t_c_p_d: $("#total_t_c_p_d").val(),
 
        }
        ,
        success: function (response) {
             alert(response);
             bootbox.hideAll();
            if (response) {
                notificacion('Accion realizada con exito', 'success');
            } else {
                notificacion('Error al guardar datos', 'error');
            }
            datatable.reload()
        }
    }
    );
}