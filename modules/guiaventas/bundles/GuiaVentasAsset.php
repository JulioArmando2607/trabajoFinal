<?php

namespace app\modules\guiaventas\bundles;

use yii\web\AssetBundle;

class GuiaVentasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/guiaventas/assets';
    public $css = [
        'css/main.css',
        'images/logo.png'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/buscarentidad.js',
        'js/facturacion.js',
        'js/validacion.js',
        'js/exportar.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
