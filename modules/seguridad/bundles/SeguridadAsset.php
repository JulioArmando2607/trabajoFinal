<?php

namespace app\modules\seguridad\bundles;

use yii\web\AssetBundle;

class SeguridadAsset extends AssetBundle {

    public $sourcePath = '@app/modules/seguridad/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/modulo.js',
        'js/perfil.js',
        'js/usuario.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
