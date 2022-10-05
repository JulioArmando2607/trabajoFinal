<?php

namespace app\modules\via\bundles;

use yii\web\AssetBundle;

class ViaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/via/assets';
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
