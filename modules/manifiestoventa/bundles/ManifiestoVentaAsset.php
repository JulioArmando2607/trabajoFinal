<?php

namespace app\modules\manifiestoventa\bundles;

use yii\web\AssetBundle;

class ManifiestoVentaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/manifiestoventa/assets';
    public $images = [
        'images/logo.jpeg'
    ];
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
