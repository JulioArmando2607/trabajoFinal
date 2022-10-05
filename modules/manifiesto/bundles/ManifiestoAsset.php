<?php

namespace app\modules\manifiesto\bundles;

use yii\web\AssetBundle;

class ManifiestoAsset extends AssetBundle {

    public $sourcePath = '@app/modules/manifiesto/assets';
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
