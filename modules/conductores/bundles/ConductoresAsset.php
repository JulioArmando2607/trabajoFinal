<?php

namespace app\modules\conductores\bundles;

use yii\web\AssetBundle;

class ConductoresAsset extends AssetBundle {

    public $sourcePath = '@app/modules/conductores/assets';
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
