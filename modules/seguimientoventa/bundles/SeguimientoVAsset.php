<?php

namespace app\modules\seguimientoventa\bundles;


use yii\web\AssetBundle;

class SeguimientoVAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/seguimientoventa/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/editar.js',
 
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
}
