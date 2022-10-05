<?php

namespace app\modules\pedidosclientes\bundles;

use yii\web\AssetBundle;

class PedidosClientesAsset extends AssetBundle {

    public $sourcePath = '@app/modules/pedidosclientes/assets';
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
