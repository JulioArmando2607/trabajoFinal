<?php

namespace app\modules\productosinventario\bundles;

use yii\web\AssetBundle;

class ProductosInventarioAsset extends AssetBundle {

    public $sourcePath = '@app/modules/productosinventario/assets';
    public $css = [
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
