<?php

namespace app\modules\solicitudatencionaux\bundles;

use yii\web\AssetBundle;

class SolicitudAtencionAuxAsset extends AssetBundle {

    public $sourcePath = '@app/modules/solicitudatencionaux/assets';
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
