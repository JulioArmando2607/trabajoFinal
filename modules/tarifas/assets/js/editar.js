

function funcionEditarPA(id) {
    $.post(APP_URL + '/tarifas/default/get-modal-edit-p-a/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>EDITAR TARIFA AEREA PROVINCIA </strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $("#provinciae").select2({
            placeholder: "Seleccioné"
        });
        $(document).ready(function () {
            $("#btn-guardar-tr").click(function () {
                $("#form-tarifapro").validate({
                    rules: {

                    },
                    messages: {

                    },
                    submitHandler: function () {

                        var tarifa_m_a_cg = $("#tarifa_m_a_cg").val();
                        var tarifa_m_a_vr = $("#tarifa_m_a_vr").val();
                        var tarifa_m_a_pd = $("#tarifa_m_a_pd").val();
                        var tarifa_m_a_dm = $("#tarifa_m_a_dm").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tarifas/default/update-p-a',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id: id,
                                tarifa_m_a_cg: tarifa_m_a_cg,
                                tarifa_m_a_vr: tarifa_m_a_vr,
                                tarifa_m_a_pd: tarifa_m_a_pd,
                                tarifa_m_a_dm: tarifa_m_a_dm,

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


function funcionEditarPT(id) {
    $.post(APP_URL + '/tarifas/default/get-modal-edit-p-t/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>EDITAR TARIFA TERR PROVINCIA</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $("#provinciae").select2({
            placeholder: "Seleccioné"
        });
        $(document).ready(function () {
            $("#btn-guardar-tarp").click(function () {
                $("#form-tarifaproe").validate({
                    rules: {

                    },
                    messages: {

                    },
                    submitHandler: function () {
                        var tarifa_m_t_ref = $("#tarifa_m_t_ref").val();
                        var tarifa_m_t_vol = $("#tarifa_m_t_vol").val();
                        var tarifa_m_t_dm = $("#tarifa_m_t_dm").val();
                        var tarifa_m_t_cg = $("#tarifa_m_t_cg").val();


                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/tarifas/default/update-p-t',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                id: id,
                                tarifa_m_t_cg: tarifa_m_t_cg,
                                tarifa_m_t_dm: tarifa_m_t_dm,
                                tarifa_m_t_vol: tarifa_m_t_vol,
                                tarifa_m_t_ref: tarifa_m_t_ref,

                            },

                            success: function (response) {
                                console.log(" " + response);
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatablept.reload()
                            }

                        });
                    }
                });
            });
        });
    }, 'json');
}

function  funcionAgregarEProTerrestre(id) {
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
                        // var id_entidad_te = $("#id_entidad_te").val();
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
                                id_entidad_te: id,
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

                                datatablept.reload()
                            }
                        });
                    }
                });
            });
        });
    }, 'json');
}


function funcionEditar(id) {
    // alert(id);
    $.ajax({
        type: "POST",
        dataType: 'json', //GuardarTGA
        url: APP_URL + '/tarifas/default/editar-tarifa',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {

            id: id,
            entidad: $("#entidades").val(),
            
         //   tipotarifa:$("#tipotarifa").val(),
             
            tarifa_m_t_costo: $("#costo_t_c_p_d").val(),
            tarifa_m_t_igv: $("#igv_t_c_p_d").val(),
            tarifa_m_t_total: $("#total_t_c_p_d").val(),

            tarifa_m_a_c: $("#costo").val(),
            tarifa_m_a_igv: $("#igv").val(),
            tarifa_m_a_total: $("#igv_total_a").val(),

            costo_t_a_ref: $("#costo_t_a_ref").val(),
            igv_t_a_ref: $("#igv_t_a_ref").val(),
            total_t_a_ref: $("#total_t_a_ref").val(),

            costo_t_a_pel: $("#costo_t_a_pel").val(),
            igv_t_a_pel: $("#igv_t_a_pel").val(),
            total_t_a_pel: $("#total_t_a_pel").val(),

            tarifa_m_t_c_pel: $("#tarifa_m_t_c_pel").val(),
            tarifa_m_t_igv_pel: $("#tarifa_m_t_igv_pel").val(),
            tarifa_m_t_total_pel: $("#tarifa_m_t_total_pel").val(),

            tarifa_peso_a_base_general: $("#tarifa_peso_a_base_general").val(),
            tarifa_peso_a_base_ref: $("#tarifa_peso_a_base_ref").val(),
            tarifa_peso_a_base_pel: $("#tarifa_peso_a_base_pel").val(),

            tarifa_peso_t_base_general: $("#tarifa_peso_t_base_general").val(),
            tarifa_peso_t_base_ref: $("#tarifa_peso_t_base_ref").val(),
            tarifa_peso_t_base_pel: $("#tarifa_peso_t_base_pel").val(),




        }
        ,
        success: function (response) {
            //alert(response);
            // bootbox.hideAll();
            if (response) {
                notificacion('Accion realizada con exito', 'success');
            } else {
                notificacion('Error al guardar datos', 'error');
            }
            datatable.reload()
            //datatablept.search();
        }
    }
    );
}


function  funcionAgregarEPro() {
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
        $("#provincia").select2({
            placeholder: "Seleccioné Ubigeo"
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
                                alert(response)
                                bootbox.hideAll();
                                console.log(response)
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


function  funcionAgregarProETerrestre() {
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
        $("#id_entidad_te").val($("#entidad_edit").val());
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
