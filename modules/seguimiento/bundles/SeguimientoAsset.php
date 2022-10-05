<?php

namespace app\modules\seguimiento\bundles;


use yii\web\AssetBundle;

class SeguimientoAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/seguimiento/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/editar.js',
        'js/editarc.js',
        'js/solicitarPermiso.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
}
