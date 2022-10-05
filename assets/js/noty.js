toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "600",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

function notificacion(mgs, type) {
    switch (type) {
        case 'error':
            toastr.error(mgs, "Alerta");
            break;
        case 'success':
            toastr.success(mgs, "Alerta");
            break;
        case 'info':
            toastr.info(mgs, "Alerta");
            break;
        case 'warning':
            toastr.warning(mgs, "Alerta");
            break;
        default:
            toastr.warning(mgs, "Alerta");
            break;
    }

}



