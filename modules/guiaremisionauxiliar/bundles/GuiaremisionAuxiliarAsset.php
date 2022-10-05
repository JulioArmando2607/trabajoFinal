<?php

namespace app\modules\guiaremisionauxiliar\bundles;

use yii\web\AssetBundle;

class GuiaremisionAuxiliarAsset extends AssetBundle {

    public $sourcePath = '@app/modules/guiaremisionauxiliar/assets';
    public $css = [
        'css/main.css'
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
