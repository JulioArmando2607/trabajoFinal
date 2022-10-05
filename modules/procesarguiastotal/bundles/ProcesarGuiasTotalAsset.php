<?php

namespace app\modules\procesarguiastotal\bundles;

use yii\web\AssetBundle;

class ProcesarGuiasTotalAsset extends AssetBundle {

    public $sourcePath = '@app/modules/procesarguiastotal/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/procesar.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
