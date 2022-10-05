<?php

namespace app\modules\externo\controllers;

use app\components\Utils;
use app\models\GuiaRemision;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;

/**
 * Default controller for the `externo` module
 */
class ApiController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionStatus($id)
    {
        $code_hash = $_REQUEST["code"];
        if (Yii::$app->security->validatePassword($id, $code_hash)) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $seguimiento = GuiaRemision::findOne($id);
                $seguimiento->flg_guia = 0;

                $seguimiento->id_usuario_act = Yii::$app->user->getId();
                $seguimiento->fecha_act = Utils::getFechaActual();
                $seguimiento->ipmaq_act = Utils::obtenerIP();

                if (!$seguimiento->save()) {
                    Utils::show($seguimiento->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            return $this->render('index');
        } else {
            echo 'Codigo no permitido';
        }
    }

    public function actionGenerate($id)
    {
        return Yii::$app->security->generatePasswordHash($id);
    }


}
