<?php

namespace app\modules\seguimientocliente\bundles;


use yii\web\AssetBundle;

class SeguimientoClienteAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/seguimientocliente/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/editar.js',
        'js/editarc.js',
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
}
