<?php

namespace app\modules\prueba\controllers;

use app\components\Menu;
use yii\web\Controller;

/**
 * Default controller for the `prueba` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {


        return $this->render('index', [

        ]);
    }



}
