<?php

namespace app\modules\liquidacion\bundles;

use yii\web\AssetBundle;

class LiquidacionAsset extends AssetBundle {

    public $sourcePath = '@app/modules/liquidacion/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/liquidar.js',
        'js/exportar.js',

    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
