<?php

namespace app\modules\reportekilometraje\bundles;

use yii\web\AssetBundle;

class ReporteKilometrajeAsset extends AssetBundle {

    public $sourcePath = '@app/modules/reportekilometraje/assets';
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
