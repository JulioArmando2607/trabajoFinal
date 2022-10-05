<?php

namespace app\modules\tipoestado\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\TipoEstado;
use yii\filters\AccessControl;

/**
 * Default controller for the `tipoestado` module
 */
class DefaultController extends Controller {

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
                            'lista',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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


                $tipoestado = new TipoEstado();

                $tipoestado->siglas = $post['siglas'];
                $tipoestado->nombre_tipo = $post['nombre_tipo'];
                $tipoestado->flg_estado = Utils::ACTIVO;
                $tipoestado->id_usuario_reg = Yii::$app->user->getId();
                $tipoestado->fecha_reg = Utils::getFechaActual();
                $tipoestado->ipmaq_reg = Utils::obtenerIP();

                if (!$tipoestado->save()) {
                    Utils::show($tipoestado->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }


            Utils::jsonEncode($tipoestado->id_tipo_estado);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = TipoEstado::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "tipoestado" => $data
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
                $tipoestado = TipoEstado::findOne($post['id_tipo_estado']);
                /* siglas: "required",
                  nombre_tipo: "required", */
                $tipoestado->siglas = $post['siglas'];
                $tipoestado->nombre_tipo = $post['nombre_tipo'];

                //            $tipoestado->flg_estado = 1;
                $tipoestado->id_usuario_act = Yii::$app->user->getId();
                $tipoestado->fecha_act = Utils::getFechaActual();
                $tipoestado->ipmaq_act = Utils::obtenerIP();

                if (!$tipoestado->update()) {
                    Utils::show($tipoestado->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Utils::jsonEncode($tipoestado->id_tipo_estado);
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
                $tipoestado = TipoEstado::findOne($post['id_tipo_estado']);
                $tipoestado->id_usuario_del = Yii::$app->user->getId();
                $tipoestado->fecha_del = Utils::getFechaActual();
                $tipoestado->ipmaq_del = Utils::obtenerIP();

                if (!$tipoestado->save()) {
                    Utils::show($tipoestado->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro tipoestado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Utils::jsonEncode($tipoestado->id_tipo_estado);
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
            $command = Yii::$app->db->createCommand('call listadotipoestado(:row,:length,:buscar)');
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
                "siglas" => $row['siglas'],
                "nombre_tipo" => $row['nombre_tipo'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_tipo_estado"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_tipo_estado"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
        Utils::jsonEncode($json_data);
    }

}
