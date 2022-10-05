<?php

namespace app\modules\estados\bundles;

use yii\web\AssetBundle;

class EstadosAsset extends AssetBundle {

    public $sourcePath = '@app/modules/estados/assets';
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
