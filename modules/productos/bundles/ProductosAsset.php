<?php

namespace app\modules\productos\bundles;

use yii\web\AssetBundle;

class ProductosAsset extends AssetBundle {

    public $sourcePath = '@app/modules/productos/assets';
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
