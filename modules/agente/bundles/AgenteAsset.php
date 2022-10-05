<?php

namespace app\modules\agente\bundles;

use yii\web\AssetBundle;

class AgenteAsset extends AssetBundle {

    public $sourcePath = '@app/modules/agente/assets';
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
