<?php

namespace app\modules\estados\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Estados;
use app\models\TipoEstado;

/**
 * Default controller for the `estados` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;

    //Hola, soy Franklin y yo Dayron
// hola soy paolo 
    // soy marco xd

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGetModal() {
        $tipo_estado = TipoEstado::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "tipo_estado" => $tipo_estado
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {
        $tipo_estado = TipoEstado::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
                    "tipo_estado" => $tipo_estado,
        ]);
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                $estados = new Estados();
                $estados->nombre_estado = $post['nombre_estado'];
                $estados->id_tipo_estado = $post['tipo_estado'];
                $estados->siglas = $post['siglas'];
                $estados->flg_estado = Utils::ACTIVO;
                $estados->id_usuario_reg = Yii::$app->user->getId();
                $estados->fecha_reg = Utils::getFechaActual();
                $estados->ipmaq_reg = Utils::obtenerIP();

                if (!$estados->save()) {
                    Utils::show($estados->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $estados->id_estado;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $tipo_estado = TipoEstado::find()->where(["fecha_del" => null])->all();
        $data = Estados::findOne($id);

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "estados" => $data,
            "tipo_estado" => $tipo_estado,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $estados = Estados::findOne($post['id_estado']);
                $estados->nombre_estado = $post['nombre_estado'];
                $estados->id_tipo_estado = $post['tipo_estado'];
                $estados->siglas = $post['siglas'];
                $estados->id_usuario_act = Yii::$app->user->getId();
                $estados->fecha_act = Utils::getFechaActual();
                $estados->ipmaq_act = Utils::obtenerIP();

                if (!$estados->update()) {
                    Utils::show($estados->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $estados->id_estado;

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
                $estados = Estados::findOne($post['id_estado']);
                $estados->id_usuario_del = Yii::$app->user->getId();
                $estados->fecha_del = Utils::getFechaActual();
                $estados->ipmaq_del = Utils::obtenerIP();

                if (!$estados->save()) {
                    Utils::show($estados->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro estados");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($estados->id_estado);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $estados->id_estado;

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
            $command = Yii::$app->db->createCommand('call listadoEstados(:row,:length,:buscar)');
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
                "nombre_tipo" => $row['nombre_tipo'],
                "siglas" => $row['siglas'],
                "siglas" => $row['siglas'],
                "nombre_estado" => $row['nombre_estado'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_estado"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_estado"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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

}
