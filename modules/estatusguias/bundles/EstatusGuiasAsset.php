<?php

namespace app\modules\estatusguias\bundles;

use yii\web\AssetBundle;

class EstatusGuiasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/estatusguias/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/exportar.js',
      
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
