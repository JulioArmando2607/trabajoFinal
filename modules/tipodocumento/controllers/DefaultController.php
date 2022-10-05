<?php

namespace app\modules\tipodocumento\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\TipoDocumentos;
use yii\filters\AccessControl;
/**
 * Default controller for the `tipodocumento` module
 */
class DefaultController extends Controller
{ 
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

             
                $tipodocumento = new TipoDocumentos();

             
                $tipodocumento->documento = $post['documento'];
                $tipodocumento->flg_estado = Utils::ACTIVO;
                $tipodocumento->id_usuario_reg = Yii::$app->user->getId();
                $tipodocumento->fecha_reg = Utils::getFechaActual();
                $tipodocumento->ipmaq_reg = Utils::obtenerIP();

                if (!$tipodocumento->save()) {
                    Utils::show($tipodocumento->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($tipodocumento->id_tipo_documento);
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tipodocumento->id_tipo_documento;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = TipoDocumentos::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "tipodocumento" => $data
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
                $tipodocumento = TipoDocumentos::findOne($post['id_tipo_documento']);
                   /*siglas: "required",
                        documento: "required",*/
          
                $tipodocumento->documento = $post['documento'];

    //            $tipodocumento->flg_estado = 1;
                $tipodocumento->id_usuario_act = Yii::$app->user->getId();
                $tipodocumento->fecha_act = Utils::getFechaActual();
                $tipodocumento->ipmaq_act = Utils::obtenerIP();

                if (!$tipodocumento->update()) {
                    Utils::show($tipodocumento->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Tipo Estado");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($tipodocumento->id_tipo_documento);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tipodocumento->id_tipo_documento;

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
                $tipodocumento = TipoDocumentos::findOne($post['id_tipo_documento']);
                $tipodocumento->id_usuario_del = Yii::$app->user->getId();
                $tipodocumento->fecha_del = Utils::getFechaActual();
                $tipodocumento->ipmaq_del = Utils::obtenerIP();

                if (!$tipodocumento->save()) {
                    Utils::show($tipodocumento->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro tipodocumento");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
           // echo json_encode($tipodocumento->id_tipo_documento);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $tipodocumento->id_tipo_documento;

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
            $command = Yii::$app->db->createCommand('call listadotipodocumento(:row,:length,:buscar)');
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
              
                "documento" => $row['documento'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_tipo_documento"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_tipo_documento"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
