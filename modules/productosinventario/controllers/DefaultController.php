<?php

namespace app\modules\productosinventario\controllers;

use app\components\Utils;
use app\models\ProductosInventario;
use yii\web\Controller;

use yii\filters\AccessControl;

use Yii;

/**
 * Default controller for the `productosinventario` module
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

        $plantilla = Yii::$app->controller->renderPartial("crear", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }
    public function actionGetModalEdit($id) {
        $data = ProductosInventario::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "producto" => $data
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }


    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $productos= new ProductosInventario();
                $productos->nombre = $post['nombre'];
                $productos->precio = $post['precio'];
                $productos->cantidad = $post['cantidad'];
                $productos->medida = $post['medida'];
                $productos->descripcion = $post['descripcion'];
                $productos->id_usuario_reg = Yii::$app->user->getId();
                $productos->fecha_reg = Utils::getFechaActual();
                $productos->ipmaq_reg = Utils::obtenerIP();

                if (!$productos->save()) {
                    Utils::show($productos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Cliente");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($productos->id_producto);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $productos = ProductosInventario::findOne($post['id_producto']);
                $productos->nombre = $post['nombre'];
                $productos->precio = $post['precio'];
                $productos->cantidad = $post['cantidad'];
                $productos->medida = $post['medida'];
                $productos->descripcion = $post['descripcion'];
                $productos->id_usuario_act = Yii::$app->user->getId();
                $productos->fecha_act = Utils::getFechaActual();
                $productos->ipmaq_act = Utils::obtenerIP();

                if (!$productos->save()) {
                    Utils::show($productos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Cliente");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($productos->id_producto);
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
//        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoProductosVenta(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "item" => $row['item'],
                "nombre" => $row['nombre'],
                "precio" => $row['precio'],
                "cantidad" => $row['cantidad'],
                "medida" => $row['medida'],
                "descripcion" => $row['descripcion'],
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

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id
                $persona = ProductosInventario::findOne($post['id_producto']);
                $persona->id_usuario_del = Yii::$app->user->getId();
                $persona->fecha_del = Utils::getFechaActual();
                $persona->ipmaq_del = Utils::obtenerIP();

                if (!$persona->save()) {
                    Utils::show($persona->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            echo json_encode($persona->id_producto);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

}
