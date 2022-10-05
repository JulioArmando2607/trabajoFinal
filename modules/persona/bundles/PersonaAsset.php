<?php

namespace app\modules\persona\bundles;

use yii\web\AssetBundle;

class PersonaAsset extends AssetBundle {

    public $sourcePath = '@app/modules/persona/assets';
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
