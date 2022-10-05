<?php

namespace app\modules\guiaremision\bundles;

use yii\web\AssetBundle;

class GuiaremisionAsset extends AssetBundle {

    public $sourcePath = '@app/modules/guiaremision/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/exportar.js',
        'js/solicitarPermiso.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
