<?php

namespace app\modules\ubigeos\bundles;

use yii\web\AssetBundle;

class UbigeosAsset extends AssetBundle {

    public $sourcePath = '@app/modules/ubigeos/assets';
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
