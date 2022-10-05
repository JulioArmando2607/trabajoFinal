<?php

namespace app\modules\reportetecnocom\bundles;

use yii\web\AssetBundle;

class ReporteTecnocomAsset extends AssetBundle {

    public $sourcePath = '@app/modules/reportetecnocom/assets';
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
