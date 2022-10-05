<?php

namespace app\modules\tarifas\bundles;

use yii\web\AssetBundle;

class TarifasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/tarifas/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/provincias.js',
        'js/provincias_terrestre.js',
        'js/calculos.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
