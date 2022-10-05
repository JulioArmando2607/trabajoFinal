<?php

namespace app\modules\tarifas\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use \app\models\TarifaProvinciaEnt;
use app\models\Via;
use app\components\Utils;
use app\models\ClientesVentas;
use app\models\Agente;
use app\modules\guiaventas\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\FormaPago;
use app\models\TipoComprobante;
use app\models\TipoDocumentos;
use app\models\TipoEntrega;
//models
use app\models\Ubigeos;

//use yii\filters\AccessControl;

/**
 * Default controller for the `tarifas` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'editar',
                            'get-modal-edit-p-a',
                            'get-modal-edit-p-t',
                            'update-p-t',
                            'update-p-a',
                            'crear-tarifa',
                            'guardar-tarifa',
                            'editar-tarifa',
                            'guardar-t-g-a',
                            'guardar-t-g-t',
                            'get-modal',
                            'get-modal-t',
                            'crear',
                            'create-p-t',
                            'create-p-a',
                            'delete',
                            'lista',
                            'lista-provincia-tarifa',
                            'lista-provincia-t-tarifa',
                            'lista-provincia'


                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionEditar($id) {

        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call EntidadesTarifa(:id_tarifa)');
            $command->bindValue(':id_tarifa', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $entidades = \app\models\Entidades::find()->where(["fecha_del" => null])->all();
       // $tipoTarifa = \app\models\TipoTarifa::find()->where(["fecha_del" => null])->all();
        $tarifaEnt = \app\models\TarifaEntidad::findOne($id);


        return $this->render('editar', [
                    "tarifaEnt" => $tarifaEnt,
                    "entidades" => $entidades,
                    "tarifaentidad" => $result,
         ///           "tipoTarifa" => $tipoTarifa,
        ]);
    }

    public function actionGetModalEditPA($id) {
        $data = \app\models\TarifaProvinciaEnt::findOne($id);
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvincia()');

            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $plantilla = Yii::$app->controller->renderPartial("editarP", [
            "tarifaprovincia" => $data,
            "provincia" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionGetModalEditPT($id) {
        $data = \app\models\TarifaProvinciaEnt::findOne($id);
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvincia()');

            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $plantilla = Yii::$app->controller->renderPartial("editarPT", [
            "tarifaprovincia" => $data,
            "provincia" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdatePT() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaprovincia = \app\models\TarifaProvinciaEnt::findOne($post["id"]);

                /*     id_tarifa_pr: id,
                  tarifa_m_t_cg: tarifa_m_t_cg,
                  tarifa_m_t_dm: tarifa_m_t_dm,
                  tarifa_m_t_vol: tarifa_m_t_vol,
                  tarifa_m_t_ref: tarifa_m_t_ref, */

                $tarifaprovincia->tarifa_m_t_ref = $post['tarifa_m_t_ref'];
                $tarifaprovincia->tarifa_m_t_vol = $post['tarifa_m_t_vol'];
                $tarifaprovincia->tarifa_m_t_dm = $post['tarifa_m_t_dm'];
                $tarifaprovincia->tarifa_m_t_cg = $post['tarifa_m_t_cg'];


                $tarifaprovincia->flg_estado = Utils::ACTIVO;
                $tarifaprovincia->id_usuario_act = Yii::$app->user->getId();
                $tarifaprovincia->fecha_act = Utils::getFechaActual();
                $tarifaprovincia->ipmaq_act = Utils::obtenerIP();

                if (!$tarifaprovincia->save()) {
                    Utils::show($tarifaprovincia->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaprovincia->id_tarifa_provincia_ent;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUpdatePA() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaprovincia = \app\models\TarifaProvinciaEnt::findOne($post["id"]);

                $tarifaprovincia->tarifa_m_a_cg = $post['tarifa_m_a_cg'];
                $tarifaprovincia->tarifa_m_a_vr = $post['tarifa_m_a_vr'];
                $tarifaprovincia->tarifa_m_a_pd = $post['tarifa_m_a_pd'];
                $tarifaprovincia->tarifa_m_a_dm = $post['tarifa_m_a_dm'];


                $tarifaprovincia->flg_estado = Utils::ACTIVO;
                $tarifaprovincia->id_usuario_act = Yii::$app->user->getId();
                $tarifaprovincia->fecha_act = Utils::getFechaActual();
                $tarifaprovincia->ipmaq_act = Utils::obtenerIP();

                if (!$tarifaprovincia->save()) {
                    Utils::show($tarifaprovincia->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaprovincia->id_tarifa_provincia_ent;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCrearTarifa() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                /// $tarifaprovinciat = new TarifaProvinciaEnt();
                $tarifaui = \app\models\TarifaEntidad::find()
                                ->where(["id_entidad" => $post['entidad']])->one();
                if (empty($tarifaui)) {


                    $tarifaentidad = new \app\models\TarifaEntidad();
                    $tarifaentidad->id_entidad = $post['entidad'];

                    $tarifaentidad->tipo_tarifa = 10;

                    $tarifaentidad->tarifa_m_t_c_pel = $post['costo_t_c_p_d'];
                    $tarifaentidad->tarifa_m_t_igv_pel = $post['tarifa_m_t_igv'];
                    $tarifaentidad->tarifa_m_t_total_pel = $post['total_t_c_p_d'];

                    $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                    $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                    $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                    $tarifaentidad->tarifa_m_t_costo_ref = $post['costo_t_g_ref'];
                    $tarifaentidad->tarifa_m_t_igv_ref = $post['igv_t_g_ref'];
                    $tarifaentidad->tarifa_m_t_total_ref = $post['total_t_g_ref'];

                    $tarifaentidad->tarifa_m_a_c = $post['tarifa_m_a_c'];
                    $tarifaentidad->tarifa_m_a_igv = $post['tarifa_m_a_igv'];
                    $tarifaentidad->tarifa_m_a_total = $post['tarifa_m_a_total'];

                    $tarifaentidad->tarifa_m_a_c_ref = $post['costo_t_a_ref'];
                    $tarifaentidad->tarifa_m_a_igv_ref = $post['igv_t_a_ref'];
                    $tarifaentidad->tarifa_m_a_total_ref = $post['total_t_a_ref'];

                    $tarifaentidad->tarifa_m_a_c_pel = $post['costo_t_a_pel'];
                    $tarifaentidad->tarifa_m_a_igv_pel = $post['igv_t_a_pel'];
                    $tarifaentidad->tarifa_m_a_total_pel = $post['total_t_a_pel'];

                    $tarifaentidad->peso_a_base_general = $post['tarifa_peso_a_base_general'];
                    $tarifaentidad->peso_a_base_ref = $post['tarifa_peso_a_base_ref'];
                    $tarifaentidad->peso_a_base_pel = $post['tarifa_peso_a_base_pel'];

                    $tarifaentidad->peso_t_base_general = $post['tarifa_peso_t_base_general'];
                    $tarifaentidad->peso_t_base_ref = $post['tarifa_peso_t_base_ref'];
                    $tarifaentidad->peso_t_base_pel = $post['tarifa_peso_t_base_pel'];


                    $tarifaentidad->flg_estado = Utils::ACTIVO;
                    $tarifaentidad->id_usuario_reg = Yii::$app->user->getId();
                    $tarifaentidad->fecha_reg = Utils::getFechaActual();
                    $tarifaentidad->ipmaq_reg = Utils::obtenerIP();

                    if (!$tarifaentidad->save()) {
                        Utils::show($tarifaentidad->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }
                    $transaction->commit();
                } else {

                    $tarifaentidad = \app\models\TarifaEntidad::findOne(
                                    $tarifaui->id_tarifa);

                    $tarifaentidad->tipo_tarifa =10;
                    $tarifaentidad->tarifa_m_t_c_pel = $post['costo_t_c_p_d'];
                    $tarifaentidad->tarifa_m_t_igv_pel = $post['tarifa_m_t_igv'];
                    $tarifaentidad->tarifa_m_t_total_pel = $post['total_t_c_p_d'];

                    $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                    $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                    $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                    $tarifaentidad->tarifa_m_t_costo_ref = $post['costo_t_g_ref'];
                    $tarifaentidad->tarifa_m_t_igv_ref = $post['igv_t_g_ref'];
                    $tarifaentidad->tarifa_m_t_total_ref = $post['total_t_g_ref'];

                    $tarifaentidad->tarifa_m_a_c = $post['tarifa_m_a_c'];
                    $tarifaentidad->tarifa_m_a_igv = $post['tarifa_m_a_igv'];
                    $tarifaentidad->tarifa_m_a_total = $post['tarifa_m_a_total'];

                    $tarifaentidad->tarifa_m_a_c_ref = $post['costo_t_a_ref'];
                    $tarifaentidad->tarifa_m_a_igv_ref = $post['igv_t_a_ref'];
                    $tarifaentidad->tarifa_m_a_total_ref = $post['total_t_a_ref'];

                    $tarifaentidad->tarifa_m_a_c_pel = $post['costo_t_a_pel'];
                    $tarifaentidad->tarifa_m_a_igv_pel = $post['igv_t_a_pel'];
                    $tarifaentidad->tarifa_m_a_total_pel = $post['total_t_a_pel'];

                    $tarifaentidad->peso_a_base_general = $post['tarifa_peso_a_base_general'];
                    $tarifaentidad->peso_a_base_ref = $post['tarifa_peso_a_base_ref'];
                    $tarifaentidad->peso_a_base_pel = $post['tarifa_peso_a_base_pel'];

                    $tarifaentidad->peso_t_base_general = $post['tarifa_peso_t_base_general'];
                    $tarifaentidad->peso_t_base_ref = $post['tarifa_peso_t_base_ref'];
                    $tarifaentidad->peso_t_base_pel = $post['tarifa_peso_t_base_pel'];

                    $tarifaentidad->flg_estado = Utils::ACTIVO;
                    $tarifaentidad->id_usuario_reg = Yii::$app->user->getId();
                    $tarifaentidad->fecha_reg = Utils::getFechaActual();
                    $tarifaentidad->ipmaq_reg = Utils::obtenerIP();

                    if (!$tarifaentidad->save()) {
                        Utils::show($tarifaentidad->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }


                    $transaction->commit();
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaentidad->id_tarifa;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGuardarTarifa() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaentidad = new \app\models\TarifaEntidad();
                $tarifaentidad->id_entidad = $post['entidad'];
                $tarifaentidad->tipo_tarifa = 10;
                //   $tarifaentidad->id_via = 2;

                $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                $tarifaentidad->tarifa_m_a_c = $post['tarifa_m_a_c'];
                $tarifaentidad->tarifa_m_a_igv = $post['tarifa_m_a_igv'];
                $tarifaentidad->tarifa_m_a_total = $post['tarifa_m_a_total'];

                $tarifaentidad->tarifa_m_a_c_ref = $post['costo_t_a_ref'];
                $tarifaentidad->tarifa_m_a_igv_ref = $post['igv_t_a_ref'];
                $tarifaentidad->tarifa_m_a_total_ref = $post['total_t_a_ref'];

                $tarifaentidad->tarifa_m_a_c_pel = $post['costo_t_a_pel'];
                $tarifaentidad->tarifa_m_a_igv_pel = $post['igv_t_a_pel'];
                $tarifaentidad->tarifa_m_a_total_pel = $post['total_t_a_pel'];

                $tarifaentidad->flg_estado = Utils::ACTIVO;
                $tarifaentidad->id_usuario_reg = Yii::$app->user->getId();
                $tarifaentidad->fecha_reg = Utils::getFechaActual();
                $tarifaentidad->ipmaq_reg = Utils::obtenerIP();

                if (!$tarifaentidad->save()) {
                    Utils::show($tarifaentidad->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaentidad->id_tarifa;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditarTarifa() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaentidad = \app\models\TarifaEntidad::findOne($post["id"]);
                // $tarifaentidad->id_entidad = $post['entidad'];
                //   $tarifaentidad->id_via = 2;
              // $tarifaentidad->tipo_tarifa = $post['tipotarifa'];
                $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                $tarifaentidad->tarifa_m_a_c = $post['tarifa_m_a_c'];
                $tarifaentidad->tarifa_m_a_igv = $post['tarifa_m_a_igv'];
                $tarifaentidad->tarifa_m_a_total = $post['tarifa_m_a_total'];

                $tarifaentidad->tarifa_m_a_c_ref = $post['costo_t_a_ref'];
                $tarifaentidad->tarifa_m_a_igv_ref = $post['igv_t_a_ref'];
                $tarifaentidad->tarifa_m_a_total_ref = $post['total_t_a_ref'];

                $tarifaentidad->tarifa_m_a_c_pel = $post['costo_t_a_pel'];
                $tarifaentidad->tarifa_m_a_igv_pel = $post['igv_t_a_pel'];
                $tarifaentidad->tarifa_m_a_total_pel = $post['total_t_a_pel'];

                $tarifaentidad->tarifa_m_t_c_pel = $post['tarifa_m_t_c_pel'];
                $tarifaentidad->tarifa_m_t_igv_pel = $post['tarifa_m_t_igv_pel'];
                $tarifaentidad->tarifa_m_t_total_pel = $post['tarifa_m_t_total_pel'];

                $tarifaentidad->peso_a_base_general = $post['tarifa_peso_a_base_general'];
                $tarifaentidad->peso_a_base_ref = $post['tarifa_peso_a_base_ref'];
                $tarifaentidad->peso_a_base_pel = $post['tarifa_peso_a_base_pel'];

                $tarifaentidad->peso_t_base_general = $post['tarifa_peso_t_base_general'];
                $tarifaentidad->peso_t_base_ref = $post['tarifa_peso_t_base_ref'];
                $tarifaentidad->peso_t_base_pel = $post['tarifa_peso_t_base_pel'];

                /* $tarifaentidad->tarifa_m_t_c_pel = $post['costo_t_c_p_d'];
                  $tarifaentidad->tarifa_m_t_igv_pel = $post['tarifa_m_t_igv'];
                  $tarifaentidad->tarifa_m_t_total_pel = $post['total_t_c_p_d'];

                  $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                  $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                  $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                  $tarifaentidad->tarifa_m_t_costo_ref = $post['costo_t_g_ref'];
                  $tarifaentidad->tarifa_m_t_igv_ref = $post['igv_t_g_ref'];
                  $tarifaentidad->tarifa_m_t_total_ref = $post['total_t_g_ref']; */


                $tarifaentidad->flg_estado = Utils::ACTIVO;
                $tarifaentidad->id_usuario_act = Yii::$app->user->getId();
                $tarifaentidad->fecha_act = Utils::getFechaActual();
                $tarifaentidad->ipmaq_act = Utils::obtenerIP();

                if (!$tarifaentidad->save()) {
                    Utils::show($tarifaentidad->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaentidad->id_tarifa;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGuardarTGA() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaentidad = new \app\models\TarifaEntidad();
                $tarifaentidad->id_entidad = $post['entidad'];

                //$tarifaentidad->id_via = 2;

                $tarifaentidad->tarifa_m_a_c = $post['tarifa_m_a_c'];
                $tarifaentidad->tarifa_m_a_igv = $post['tarifa_m_a_igv'];
                $tarifaentidad->tarifa_m_a_total = $post['tarifa_m_a_total'];

                $tarifaentidad->tarifa_m_a_c_ref = $post['costo_t_a_ref'];
                $tarifaentidad->tarifa_m_a_igv_ref = $post['igv_t_a_ref'];
                $tarifaentidad->tarifa_m_a_total_ref = $post['total_t_a_ref'];

                $tarifaentidad->tarifa_m_a_c_pel = $post['costo_t_a_pel'];
                $tarifaentidad->tarifa_m_a_igv_pel = $post['igv_t_a_pel'];
                $tarifaentidad->tarifa_m_a_total_pel = $post['total_t_a_pel'];

                $tarifaentidad->tarifa_m_t_costo = $post['tarifa_m_t_costo'];
                $tarifaentidad->tarifa_m_t_igv = $post['tarifa_m_t_igv'];
                $tarifaentidad->tarifa_m_t_total = $post['tarifa_m_t_total'];

                $tarifaentidad->flg_estado = Utils::ACTIVO;
                $tarifaentidad->id_usuario_reg = Yii::$app->user->getId();
                $tarifaentidad->fecha_reg = Utils::getFechaActual();
                $tarifaentidad->ipmaq_reg = Utils::obtenerIP();

                if (!$tarifaentidad->save()) {
                    Utils::show($tarifaentidad->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaentidad->id_tarifa;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGuardarTGT() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {

                $tarifaentidad = new \app\models\TarifaEntidad();
                $tarifaentidad->id_entidad = $post['entidad'];

                $tarifaentidad->id_via = 1;

                $tarifaentidad->tarifa_m_t_costo = $post['costo'];
                $tarifaentidad->tarifa_m_t_igv = $post['igv'];
                $tarifaentidad->tarifa_m_t_total = $post['total'];


                $tarifaentidad->flg_estado = Utils::ACTIVO;
                $tarifaentidad->id_usuario_reg = Yii::$app->user->getId();
                $tarifaentidad->fecha_reg = Utils::getFechaActual();
                $tarifaentidad->ipmaq_reg = Utils::obtenerIP();

                if (!$tarifaentidad->save()) {
                    Utils::show($tarifaentidad->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaentidad->id_tarifa;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModal() {

        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvincia()');

            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearP", [
            "ubigeos" => $ubigeos,
            "provincia" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionGetModalT() {

        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvincia()');

            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $plantilla = Yii::$app->controller->renderPartial("crearPT", [
            "provincia" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {


        $tarifaprovinciat = \app\models\Entidades::find()->where(["fecha_del" => null])->all();
      //  $tipoTarifa = \app\models\TipoTarifa::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
                    "entidades" => $tarifaprovinciat,
                   // "tipoTarifa" => $tipoTarifa
        ]);
    }

    public function actionCreatePT() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                /// $tarifaprovinciat = new TarifaProvinciaEnt();
                $tarifaui = TarifaProvinciaEnt::find()->where(["id_tarifa_entidad" => $post['id_entidad_te'], "id_provincia" => $post['provincia_te']])->one();
                if (empty($tarifaui)) {


                    $tarifaprovinciat = new \app\models\TarifaProvinciaEnt();
                    $tarifaprovinciat->id_provincia = $post['provincia_te'];
                    $tarifaprovinciat->id_tarifa_entidad = $post['id_entidad_te'];

                    $tarifaprovinciat->tarifa_m_t_ref = $post['refrigeradas_te'];
                    #   $tarifaprovincia->tarifa_m_t_vol = $post['tarifa_m_t_vol'];
                    $tarifaprovinciat->tarifa_m_t_dm = $post['dificil_manejo_te'];
                    $tarifaprovinciat->tarifa_m_t_cg = $post['tarifa_terrestre'];

                    //  $tarifaprovinciat->id_via = 1;
                    $tarifaprovinciat->flg_estado = Utils::ACTIVO;
                    $tarifaprovinciat->id_usuario_reg = Yii::$app->user->getId();
                    $tarifaprovinciat->fecha_reg = Utils::getFechaActual();
                    $tarifaprovinciat->ipmaq_reg = Utils::obtenerIP();


                    if (!$tarifaprovinciat->save()) {
                        Utils::show($tarifaprovinciat->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }
                    $transaction->commit();
                } else {

                    $tarifaprovinciat = TarifaProvinciaEnt::findOne(
                                    $tarifaui->id_tarifa_provincia_ent);
                    // $tarifaprovinciat->id_tarifa_entidad = $post['id_entidad_te'];
                    $tarifaprovinciat->tarifa_m_t_ref = $post['refrigeradas_te'];
                    #   $tarifaprovincia->tarifa_m_t_vol = $post['tarifa_m_t_vol'];
                    $tarifaprovinciat->tarifa_m_t_dm = $post['dificil_manejo_te'];
                    $tarifaprovinciat->tarifa_m_t_cg = $post['tarifa_terrestre'];
                    //   $tarifaprovinciat->id_via = 1;
                    $tarifaprovinciat->flg_estado = Utils::ACTIVO;
                    $tarifaprovinciat->id_usuario_act = Yii::$app->user->getId();
                    $tarifaprovinciat->fecha_act = Utils::getFechaActual();
                    $tarifaprovinciat->ipmaq_act = Utils::obtenerIP();

                    if (!$tarifaprovinciat->save()) {
                        Utils::show($tarifaprovinciat->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }


                    $transaction->commit();
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaprovinciat->id_tarifa_provincia_ent;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCreatePA() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();


            try {
                /// $tarifaprovinciat = new TarifaProvinciaEnt();
                $tarifaui = TarifaProvinciaEnt::find()->where(["id_tarifa_entidad" => $post['entidad'], "id_provincia" => $post['provincia']])->one();
                if (empty($tarifaui)) {

                    $tarifaprovinciat = new TarifaProvinciaEnt();
                    $tarifaprovinciat->id_provincia = $post['provincia'];

                    $tarifaprovinciat->id_tarifa_entidad = $post['entidad'];

                    $tarifaprovinciat->tarifa_m_a_cg = $post['tarifa'];
                    $tarifaprovinciat->tarifa_m_a_vr = $post['vacunas_ref'];
                    $tarifaprovinciat->tarifa_m_a_pd = $post['mercancia_pel'];
                    $tarifaprovinciat->tarifa_m_a_dm = $post['dificil_manejo'];

                    //  $tarifaprovinciat->id_via = 2;
                    $tarifaprovinciat->flg_estado = Utils::ACTIVO;
                    $tarifaprovinciat->id_usuario_reg = Yii::$app->user->getId();
                    $tarifaprovinciat->fecha_reg = Utils::getFechaActual();
                    $tarifaprovinciat->ipmaq_reg = Utils::obtenerIP();

                    if (!$tarifaprovinciat->save()) {
                        Utils::show($tarifaprovinciat->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }

                    $transaction->commit();
                } else {

                    $tarifaprovinciat = TarifaProvinciaEnt::findOne(
                                    $tarifaui->id_tarifa_provincia_ent);
                    //$tarifaprovinciat->id_provincia = $post['provincia'];
                    //  $tarifaprovinciat->id_tarifa_entidad = $post['entidad'];

                    $tarifaprovinciat->tarifa_m_a_cg = $post['tarifa'];
                    $tarifaprovinciat->tarifa_m_a_vr = $post['vacunas_ref'];
                    $tarifaprovinciat->tarifa_m_a_pd = $post['mercancia_pel'];
                    $tarifaprovinciat->tarifa_m_a_dm = $post['dificil_manejo'];
                    //     $tarifaprovinciat->id_via = 2;
                    $tarifaprovinciat->flg_estado = Utils::ACTIVO;
                    $tarifaprovinciat->id_usuario_act = Yii::$app->user->getId();
                    $tarifaprovinciat->fecha_act = Utils::getFechaActual();
                    $tarifaprovinciat->ipmaq_act = Utils::obtenerIP();

                    if (!$tarifaprovinciat->save()) {
                        Utils::show($tarifaprovinciat->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }


                    $transaction->commit();
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaprovinciat->id_tarifa_provincia_ent;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $tarifaprovinciat = \app\models\TarifaProvinciaEnt::findOne($post['id']);
                $tarifaprovinciat->id_usuario_del = Yii::$app->user->getId();
                $tarifaprovinciat->fecha_del = Utils::getFechaActual();
                $tarifaprovinciat->ipmaq_del = Utils::obtenerIP();

                if (!$tarifaprovinciat->save()) {
                    Utils::show($tarifaprovinciat->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro entidades");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //    echo json_encode($tarifaprovinciat->id_entidad);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tarifaprovinciat->id_tarifa_provincia_ent;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoEntidadesT(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "razon_social" => $row['razon_social'],
                "numero_documento" => $row['numero_documento'],
                "accion" => '<a class="btn btn-sm btn-light-success font-weight-bold mr-2" href="tarifas/default/editar/' . $row["id_tarifa"] . '"><i class="flaticon-edit"></i>Editar</a>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_tarifa"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
            ];
        }

        $totalData = isset($result[0]['total']) ? $result[0]['total'] : 0;

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $totalData
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionListaProvinciaTarifa() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $entidad = empty($_POST["query"]["entidad"]) ? '' : $_POST["query"]["entidad"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvinciaTarifa(:row,:length,:buscar,:entidad,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':entidad', $entidad);
            // $command->bindValue(':tipo_via', 2);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        // print_r($result);
        // die();
        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "nombre_provincia" => $row['nombre_provincia'],
                "tarifa_m_a_cg" => $row['tarifa_m_a_cg'],
                "tarifa_m_a_vr" => $row['tarifa_m_a_vr'],
                "tarifa_m_a_pd" => $row['tarifa_m_a_pd'],
                "tarifa_m_a_dm" => $row['tarifa_m_a_dm'],
                "razon_social" => $row['razon_social'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditarPA(' . $row["id_tarifa_provincia_ent"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_tarifa_provincia_ent"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
            ];
        }

      ///  $totalData = isset($result[0]['total']) ? $result[0]['total'] : 0;

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionListaProvinciaTTarifa() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $entidad = empty($_POST["query"]["entidad"]) ? '' : $_POST["query"]["entidad"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoProvinciaTarifaT(:row,:length,:buscar,:entidad,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':entidad', $entidad);
            // $command->bindValue(':tipo_via', 1);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "nombre_provincia" => $row['nombre_provincia'],
                "tarifa_m_t_cg" => $row['tarifa_m_t_cg'],
                "tarifa_m_t_ref" => $row['tarifa_m_t_ref'],
                "tarifa_m_t_vol" => $row['tarifa_m_t_vol'],
                "tarifa_m_t_dm" => $row['tarifa_m_t_dm'],
                "razon_social" => $row['razon_social'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditarPT(' . $row["id_tarifa_provincia_ent"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_tarifa_provincia_ent"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
            ];
        }

       // $totalData = isset($result[0]['total']) ? $result[0]['total'] : 0;

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionListaProvincia() {
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call listadoProvincia()');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
    }

}
