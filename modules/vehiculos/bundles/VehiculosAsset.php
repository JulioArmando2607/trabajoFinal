<?php

namespace app\modules\vehiculos\bundles;

use yii\web\AssetBundle;

class VehiculosAsset extends AssetBundle {

    public $sourcePath = '@app/modules/vehiculos/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/reg_prev_lubri.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
