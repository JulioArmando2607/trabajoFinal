<?php

namespace app\modules\ubigeos\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Ubigeos;

/**
 * Default controller for the `ubigeo` module
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
                            'get-modal',
                            'create',
                            'get-modal-edit',
                            'update',
                            'delete',
                            'lista'

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

                $ubigeo = new Ubigeos();
                $ubigeo->ubigeo_departamento = $post['ubdep'];
                $ubigeo->ubigeo_provincia = $post['ubprov'];
                $ubigeo->ubigeo_distrito = $post['ubdistr'];
                $ubigeo->nombre_departamento = $post['departamento'];
                $ubigeo->nombre_provincia = $post['provincia'];
                $ubigeo->nombre_distrito = $post['provincia'];
                $ubigeo->flg_estado = Utils::ACTIVO;
                $ubigeo->id_usuario_reg = Yii::$app->user->getId();
                $ubigeo->fecha_reg = Utils::getFechaActual();
                $ubigeo->ipmaq_reg = Utils::obtenerIP();

                if (!$ubigeo->save()) {
                    Utils::show($ubigeo->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Ubigeos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($ubigeo->id_ubigeo);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $ubigeo->id_ubigeo;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Ubigeos::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "ubigeo" => $data
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
                $ubigeo = Ubigeos::findOne($post['id_ubigeo']);
                $ubigeo->ubigeo_departamento = $post['ubdep'];
                $ubigeo->ubigeo_provincia = $post['ubprov'];
                $ubigeo->ubigeo_distrito = $post['ubdistr'];
                $ubigeo->nombre_departamento = $post['departamento'];
                $ubigeo->nombre_provincia = $post['provincia'];
                $ubigeo->nombre_distrito = $post['distrito'];
                $ubigeo->flg_estado = Utils::ACTIVO;
                $ubigeo->id_usuario_act = Yii::$app->user->getId();
                $ubigeo->fecha_act = Utils::getFechaActual();
                $ubigeo->ipmaq_act = Utils::obtenerIP();

                if (!$ubigeo->save()) {
                    Utils::show($ubigeo->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Ubigeos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($ubigeo->id_ubigeo);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $ubigeo->id_ubigeo;
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
                $ubigeo = Ubigeos::findOne($post['id_ubigeo']);
                $ubigeo->id_usuario_del = Yii::$app->user->getId();
                $ubigeo->fecha_del = Utils::getFechaActual();
                $ubigeo->ipmaq_del = Utils::obtenerIP();

                if (!$ubigeo->save()) {
                    Utils::show($ubigeo->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro ubigeo");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //echo json_encode($ubigeo->id_ubigeo);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $ubigeo->id_ubigeo;
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
            $command = Yii::$app->db->createCommand('call listadoUbigeos(:row,:length,:buscar)');
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
                "nombre_departamento" => $row['nombre_departamento'],
                "nombre_provincia" => $row['nombre_provincia'],
                "nombre_distrito" => $row['nombre_distrito'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_ubigeo"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_ubigeo"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
