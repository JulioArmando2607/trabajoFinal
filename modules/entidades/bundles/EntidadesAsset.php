<?php

namespace app\modules\entidades\bundles;

use yii\web\AssetBundle;

class EntidadesAsset extends AssetBundle {

    public $sourcePath = '@app/modules/entidades/assets';
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
