function calcular() {

    var costo_aereo_tg = parseFloat($("#costo").val());
    var igv_tg = costo_aereo_tg * 0.18;
    $("#igv").val(igv_tg);
    var total = costo_aereo_tg + (costo_aereo_tg * 0.18);
    $("#igv_total_a").val(total);
    $("#total").prop("disabled", true);

    var costo_t_a_ref = parseFloat($("#costo_t_a_ref").val());
    var igv_t_a_ref = parseFloat(costo_t_a_ref * 0.18);
    $("#igv_t_a_ref").val(igv_t_a_ref);
    var total_t_a_ref = parseFloat(costo_t_a_ref + (costo_t_a_ref * 0.18));
    $("#total_t_a_ref").val(total_t_a_ref);

    var costo_t_a_pel = parseFloat($("#costo_t_a_pel").val());
    var igv_t_a_pel = parseFloat(costo_t_a_pel * 0.18);
    $("#igv_t_a_pel").val(igv_t_a_pel);
    var total_t_a_pel = parseFloat(costo_t_a_pel + (costo_t_a_pel * 0.18));
    $("#total_t_a_pel").val(total_t_a_pel);

    var costo_t_c_p_d = parseFloat($("#costo_t_c_p_d").val());
    var igv_t_c_p_d = parseFloat(costo_t_c_p_d * 0.18);
    $("#igv_t_c_p_d").val(igv_t_c_p_d);
    var total_t_c_p_d = parseFloat(costo_t_c_p_d + (costo_t_c_p_d * 0.18));
    $("#total_t_c_p_d").val(total_t_c_p_d);

    var costo_t_g_ref = parseFloat($("#costo_t_g_ref").val());
    var igv_t_g_ref = parseFloat(costo_t_g_ref * 0.18);
    $("#igv_t_g_ref").val(igv_t_g_ref);
    var total_t_g_ref = parseFloat(costo_t_g_ref + (costo_t_g_ref * 0.18));
    $("#total_t_g_ref").val(total_t_g_ref);

    var tarifa_m_t_costo_ref = parseFloat($("#tarifa_m_t_costo_ref").val());
    var tarifa_m_t_igv_ref = parseFloat(tarifa_m_t_costo_ref * 0.18);
    $("#tarifa_m_t_igv_ref").val(tarifa_m_t_igv_ref);
    var tarifa_m_t_total_ref = parseFloat(tarifa_m_t_costo_ref + (tarifa_m_t_costo_ref * 0.18));
    $("#tarifa_m_t_total_ref").val(tarifa_m_t_total_ref);

    var tarifa_m_t_c_pel = parseFloat($("#tarifa_m_t_c_pel").val());
    var tarifa_m_t_igv_pel = parseFloat(tarifa_m_t_c_pel * 0.18);
    $("#tarifa_m_t_igv_pel").val(tarifa_m_t_igv_pel);
    var tarifa_m_t_total_pel = parseFloat(tarifa_m_t_c_pel + (tarifa_m_t_c_pel * 0.18));
    $("#tarifa_m_t_total_pel").val(tarifa_m_t_total_pel);

    var tarifa_m_t_costo = parseFloat($("#tarifa_m_t_costo").val());
    var tarifa_m_t_igv = parseFloat(tarifa_m_t_costo * 0.18);
    $("#tarifa_m_t_igv").val(tarifa_m_t_igv);
    var tarifa_m_t_total = parseFloat(tarifa_m_t_costo + (tarifa_m_t_costo * 0.18));
    $("#tarifa_m_t_total").val(tarifa_m_t_total);


}

$("#costo").blur(function () {

    calcular()
});

$("#igv").blur(function () {

    calcular()
});

$("#costo_t_a_ref").blur(function () {

    calcular()
});

$("#igv_t_a_ref").blur(function () {

    calcular()
});

$("#costo_t_a_pel").blur(function () {

    calcular()
});

$("#igv_t_a_pel").blur(function () {

    calcular()
});

$("#costo_t_c_p_d").blur(function () {

    calcular()
});

$("#igv_t_c_p_d").blur(function () {

    calcular()
});

$("#total_t_c_p_d").blur(function () {

    calcular()
});

$("#costo_t_g_ref").blur(function () {

    calcular()
});

$("#igv_t_g_ref").blur(function () {

    calcular()
});

$("#total_t_g_ref").blur(function () {

    calcular()
});

$("#tarifa_m_t_costo_ref").blur(function () {

    calcular()
});

$("#tarifa_m_t_igv_ref").blur(function () {

    calcular()
});

$("#tarifa_m_t_total_ref").blur(function () {

    calcular()
});

$("#tarifa_m_t_c_pel").blur(function () {

    calcular()
});
$("#tarifa_m_t_igv_pel").blur(function () {

    calcular()
});
$("#tarifa_m_t_total_pel").blur(function () {

    calcular()
});

  
$("#tarifa_m_t_costo").blur(function () {

    calcular()
});
$("#tarifa_m_t_igv").blur(function () {

    calcular()
});
$("#tarifa_m_t_total").blur(function () {

    calcular()
});

