<?php

namespace app\modules\pedidoclienteop\bundles;

use yii\web\AssetBundle;

class PedidoClientesOpAsset extends AssetBundle {

    public $sourcePath = '@app/modules/pedidoclienteop/assets';
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
