<?php

namespace app\modules\manifiestoauxiliar\bundles;

use yii\web\AssetBundle;

class ManifiestoAuxiliarAsset extends AssetBundle {

    public $sourcePath = '@app/modules/manifiestoauxiliar/assets';
    public $css = [
    ];
    public $js = [
        'js/index.js',
        'js/exportar.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
