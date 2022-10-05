<?php

namespace app\modules\rendicioncuentas\bundles;

use yii\web\AssetBundle;

class RendicionCuentasAsset extends AssetBundle {

    public $sourcePath = '@app/modules/rendicioncuentas/assets';
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
