<?php

namespace app\modules\direcciones\bundles;

use yii\web\AssetBundle;

class DireccionesAsset extends AssetBundle {

    public $sourcePath = '@app/modules/direcciones/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
