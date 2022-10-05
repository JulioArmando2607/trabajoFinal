<?php

namespace app\modules\atencionpedidos\bundles;

use yii\web\AssetBundle;

class AtencionPedidosAsset extends AssetBundle {

    public $sourcePath = '@app/modules/atencionpedidos/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/index.js',
        'js/crear.js',
        'js/editar.js',
        'js/eliminar.js',
        'js/verguias.js'
    ];
    public $depends = [
        'app\bundles\TemplateAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
