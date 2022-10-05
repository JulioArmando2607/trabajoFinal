<?php

namespace app\modules\tipodocumento\bundles;

use yii\web\AssetBundle;

class TipoDocumentoAsset extends AssetBundle {

    public $sourcePath = '@app/modules/tipodocumento/assets';
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
