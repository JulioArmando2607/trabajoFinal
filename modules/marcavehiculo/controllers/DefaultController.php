<?php

namespace app\modules\marcavehiculo\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\MarcaVehiculo;
/**
 * Default controller for the `marcavehiculo` module
 */
class DefaultController extends Controller
{ public $enableCsrfValidation = false;

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

             
                $marcavehiculo = new MarcaVehiculo();

                $marcavehiculo->nombre_marca = $post['nombre_marca'];
          
                $marcavehiculo->flg_estado = Utils::ACTIVO;
                $marcavehiculo->id_usuario_reg = Yii::$app->user->getId();
                $marcavehiculo->fecha_reg = Utils::getFechaActual();
                $marcavehiculo->ipmaq_reg = Utils::obtenerIP();

                if (!$marcavehiculo->save()) {
                    Utils::show($marcavehiculo->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($marcavehiculo->id_marca);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $marcavehiculo->id_marca;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = MarcaVehiculo::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "marcavehiculo" => $data
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
                $marcavehiculo = MarcaVehiculo::findOne($post['id_marca']);
                   /*siglas: "required",
                        nombre_tipo: "required",*/
                $marcavehiculo->nombre_marca = $post['nombre_marca'];
             

    //            $marcavehiculo->flg_estado = 1;
                $marcavehiculo->id_usuario_act = Yii::$app->user->getId();
                $marcavehiculo->fecha_act = Utils::getFechaActual();
                $marcavehiculo->ipmaq_act = Utils::obtenerIP();

                if (!$marcavehiculo->update()) {
                    Utils::show($marcavehiculo->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($marcavehiculo->id_marca);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $marcavehiculo->id_marca;

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
                $marcavehiculo = MarcaVehiculo::findOne($post['id_marca']);
                $marcavehiculo->id_usuario_del = Yii::$app->user->getId();
                $marcavehiculo->fecha_del = Utils::getFechaActual();
                $marcavehiculo->ipmaq_del = Utils::obtenerIP();

                if (!$marcavehiculo->save()) {
                    Utils::show($marcavehiculo->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro marcavehiculo");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($marcavehiculo->id_marca);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $marcavehiculo->id_marca;

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
            $command = Yii::$app->db->createCommand('call listadomarcavehiculo(:row,:length,:buscar)');
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
                "nombre_marca" => $row['nombre_marca'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_marca"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_marca"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
