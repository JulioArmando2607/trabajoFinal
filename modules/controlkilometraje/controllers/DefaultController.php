<?php

namespace app\modules\controlkilometraje\controllers;

use app\components\Utils;
use app\models\ControlKilometraje;
use app\models\Ubigeos;
use app\modules\controlkilometraje\query\Consultas;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `controlkilometraje` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionGetModal() {
        $vehiculo = Consultas::getVehiculo();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "vehiculo" => $vehiculo,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionGetModalEdit($id) {
        $data = ControlKilometraje::findOne($id);
        $vehiculo = Consultas::getVehiculo();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "vehiculo" => $vehiculo,
            "control_kilometraje" => $data,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id
                $ck = ControlKilometraje::findOne($post['id_control_kilometraje']);
                $ck->id_usuario_del = Yii::$app->user->getId();
                $ck->fecha_del = Utils::getFechaActual();
                $ck->ipmaq_del = Utils::obtenerIP();

                if (!$ck->save()) {
                    Utils::show($ck->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($persona->id_persona);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $ck->id_control_kilometraje;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }


    public function actionCreate() {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                $controlkilometraje = new ControlKilometraje();
                $controlkilometraje->id_vehiculo = $post['vehiculo'];
                $controlkilometraje->hora_salida = $post['hora_salida'];
                $controlkilometraje->hora_llegada = $post['hora_llegada'];
                $controlkilometraje->kilometraje_salida = $post['kilometraje_salida'];
                $controlkilometraje->kilometraje_llegada = $post['kilometraje_llegada'];
                $controlkilometraje->kilometro_recorrido = $post['kilometro_recorrido'];
                $controlkilometraje->lugar_destino = $post['distrito'];
                $controlkilometraje->flg_estado = Utils::ACTIVO;
                $controlkilometraje->id_usuario_reg = Yii::$app->user->getId();
                $controlkilometraje->fecha_reg = Utils::getFechaActual();
                $controlkilometraje->ipmaq_reg = Utils::obtenerIP();
                if (!$controlkilometraje->save()) {
                    Utils::show($controlkilometraje->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Via");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($controlkilometraje->id_control_kilometraje);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUpdate() {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                $controlkilometraje = ControlKilometraje::findOne($post["id"]);
                $controlkilometraje->id_vehiculo = $post['vehiculo'];
                $controlkilometraje->hora_salida = $post['hora_salida'];
                $controlkilometraje->hora_llegada = $post['hora_llegada'];
                $controlkilometraje->kilometraje_salida = $post['kilometraje_salida'];
                $controlkilometraje->kilometraje_llegada = $post['kilometraje_llegada'];
                $controlkilometraje->kilometro_recorrido = $post['kilometro_recorrido'];
                $controlkilometraje->lugar_destino = $post['distrito'];
                $controlkilometraje->flg_estado = Utils::ACTIVO;
                $controlkilometraje->id_usuario_reg = Yii::$app->user->getId();
                $controlkilometraje->fecha_reg = Utils::getFechaActual();
                $controlkilometraje->ipmaq_reg = Utils::obtenerIP();
                if (!$controlkilometraje->save()) {
                    Utils::show($controlkilometraje->getErrors(), true);
                    throw new HttpException("No se puede guardar datos");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($controlkilometraje->id_control_kilometraje);
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

        $total_registro = 0;
        $result=null;
        try {
            $command = Yii::$app->db->createCommand('call listadoControlKilometraje(:row,:length,:buscar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];

        foreach ($result as $k => $row) {
            $data[] = [

                "vehiculo" => $row['vehiculo'],
                "hora_salida" => $row['hora_salida'],
                "hora_llegada" => $row['hora_llegada'],
                "kilometraje_salida" => $row['kilometraje_salida'],
                "kilometraje_llegada" => $row['kilometraje_llegada'],
                "kilometro_recorrido" => $row['kilometro_recorrido'],
                "lugar_destino" => $row['lugar_destino'],

                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_control_kilometraje"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_control_kilometraje"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
            ];
        }



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
}
