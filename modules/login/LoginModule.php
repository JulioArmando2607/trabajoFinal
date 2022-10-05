<?php

namespace app\modules\login;

/**
 * login module definition class
 */
class LoginModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\login\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'login';

    /**
     * {@inheritdoc}
     */
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
