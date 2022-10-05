<?php

namespace app\modules\productos\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Productos;

/**
 * Default controller for the `productos` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;


    /**
     * Renders the index view for the module
     * @return string
     */

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
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGetModal() {
        $plantilla = Yii::$app->controller->renderPartial("crear", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $productos = new Productos();
                $productos->cod_producto = $post['cod_producto'];
                $productos->nombre_producto = $post['nombre_producto'];
                $productos->unidad_medida = $post['unidad_medida'];
                $productos->flg_estado = Utils::ACTIVO;
                $productos->id_usuario_reg = Yii::$app->user->getId();
                $productos->fecha_reg = Utils::getFechaActual();
                $productos->ipmaq_reg = Utils::obtenerIP();

                if (!$productos->save()) {
                    Utils::show($productos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Productos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($productos->id_producto);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $productos->id_producto;

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Productos::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "productos" => $data
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
                $productos = Productos::findOne($post['id_producto']);
                $productos->cod_producto = $post['cod_producto'];
                $productos->unidad_medida = $post['unidad_medida'];
                $productos->nombre_producto = $post['nombre_producto'];
           
                $productos->id_usuario_act = Yii::$app->user->getId();
                $productos->fecha_act = Utils::getFechaActual();
                $productos->ipmaq_act = Utils::obtenerIP();

                if (!$productos->update()) {
                    Utils::show($productos->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Productos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($productos->id_producto);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $productos->id_producto;

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
                $productos = Productos::findOne($post['id_producto']);
                $productos->id_usuario_del = Yii::$app->user->getId();
                $productos->fecha_del = Utils::getFechaActual();
                $productos->ipmaq_del = Utils::obtenerIP();

                if (!$productos->save()) {
                    Utils::show($productos->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro productos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
           // echo json_encode($productos->id_producto);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $productos->id_producto;

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
            $command = Yii::$app->db->createCommand('call listadoProducto(:row,:length,:buscar)');
          
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
                "cod_producto" => $row['cod_producto'],
                "nombre_producto" => $row['nombre_producto'],
                "unidad_medida" => $row['unidad_medida'],              
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_producto"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_producto"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
