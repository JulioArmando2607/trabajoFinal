<?php

namespace app\modules\dashboard\bundles;

use yii\web\AssetBundle;

class DashboardAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/dashboard/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/report_pedidos.js',
        "https://cdn.amcharts.com/lib/4/core.js",
        "https://cdn.amcharts.com/lib/4/charts.js",
        "https://cdn.amcharts.com/lib/4/themes/animated.js",
        "https://cdn.amcharts.com/lib/5/index.js",
        "https://cdn.amcharts.com/lib/5/xy.js",
        "https://cdn.amcharts.com/lib/5/themes/Animated.js"
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
