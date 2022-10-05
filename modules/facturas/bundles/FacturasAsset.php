<?php

namespace app\modules\facturas\bundles;

use yii\web\AssetBundle;

class FacturasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/facturas/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/notacredito.js',
        'js/facturacion.js',
        'js/boletas.js',
        'js/notacredito.js'
    
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
