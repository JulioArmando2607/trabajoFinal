<?php

namespace app\modules\atenderasignacion\bundles;

use yii\web\AssetBundle;

class AtenderAsignacionAsset extends AssetBundle {

    public $sourcePath = '@app/modules/atenderasignacion/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/enviocorreocierre.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
