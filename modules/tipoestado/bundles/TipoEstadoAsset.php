<?php

namespace app\modules\tipoestado\bundles;

use yii\web\AssetBundle;

class TipoEstadoAsset extends AssetBundle {

    public $sourcePath = '@app/modules/tipoestado/assets';
    public $css = [
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
