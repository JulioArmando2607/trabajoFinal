<?php

namespace app\modules\externo;

/**
 * externo module definition class
 */
class Externo extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\externo\controllers';

    public $layout = 'main';
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
