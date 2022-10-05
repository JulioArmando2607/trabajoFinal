<?php

namespace app\modules\reporteguiasrem\bundles;

use yii\web\AssetBundle;

class ReportesGuiasRemAsset extends AssetBundle {

    public $sourcePath = '@app/modules/reporteguiasrem/assets';
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
