<?php

namespace app\modules\agente\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Agente;

/**
 * Default controller for the `agente` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGetModal() {
        $plantilla = Yii::$app->controller->renderPartial("crear", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        //console.log(""+"holaa");
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $agente = new Agente();

                $agente->cuenta = $post['cuenta'];
                $agente->agente = $post['agente'];
                $agente->flg_estado = Utils::ACTIVO;
                $agente->id_usuario_reg = Yii::$app->user->getId();
                $agente->fecha_reg = Utils::getFechaActual();
                $agente->ipmaq_reg = Utils::obtenerIP();

                if (!$agente->save()) {
                    Utils::show($agente->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Agente");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($agente->id_agente);
Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $agente->id_agente;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Agente::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "agente" => $data
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
                $agente = Agente::findOne($post['id_agente']);
                $agente->cuenta = $post['cuenta'];
                $agente->agente = $post['agente'];

    //            $agente->flg_estado = 1;
                $agente->id_usuario_act = Yii::$app->user->getId();
                $agente->fecha_act = Utils::getFechaActual();
                $agente->ipmaq_act = Utils::obtenerIP();

                if (!$agente->update()) {
                    Utils::show($agente->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Agente");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($agente->id_agente);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $agente->id_agente;

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
                $agente = Agente::findOne($post['id_agente']);
                $agente->id_usuario_del = Yii::$app->user->getId();
                $agente->fecha_del = Utils::getFechaActual();
                $agente->ipmaq_del = Utils::obtenerIP();

                if (!$agente->save()) {
                    Utils::show($agente->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro agente");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($agente->id_agente);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $agente->id_agente;

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
            $command = Yii::$app->db->createCommand('call listadoAgente(:row,:length,:buscar)');
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
                "cuenta" => $row['cuenta'],
                "agente" => $row['agente'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_agente"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_agente"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
