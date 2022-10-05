<?php

namespace app\bundles;

use yii\web\AssetBundle;

class TemplateAsset extends AssetBundle {

    public $sourcePath = '@app/assets';
    public $baseUrl = '@web';
    public $css = [
        "css/pages/login/classic/login-4.css",
        "plugins/global/plugins.bundle.css",
        "plugins/custom/prismjs/prismjs.bundle.css",
        "css/style.bundle.css",
        "css/themes/layout/header/base/light.css",
        "css/themes/layout/header/menu/light.css",
        "css/themes/layout/brand/dark.css",
        "css/themes/layout/aside/dark.css",
        "css/loader.css",
        "js/loading-js/loading.min.css"
    ];
    public $js = [
        "js/global.js",
        "plugins/global/plugins.bundle.js",
        "plugins/custom/prismjs/prismjs.bundle.js",
        "js/scripts.bundle.js",
        "js/pages/widgets.js",
        "js/tabla_general.js",
        'js/bootbox/bootbox.min.js',
        'js/bootbox/bootbox.locales.min.js',
        'js/jquery-validation/dist/jquery.validate.min.js',
        'js/noty.js',
        "js/loading-js/jquery.loading.min.js"
    ];
    public $depends = [
        'app\bundles\AppAsset',
    ];

}
