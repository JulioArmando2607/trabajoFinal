<?php

namespace app\modules\clientesventas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Productos;
use app\models\TipoEntidad;
use app\models\Ubigeos;
use app\models\TipoDocumentos;
use app\models\ClientesVentas; 
use app\models\Direcciones;
//use \app\models\ClientesVentas;

/**
 * Default controller for the `clientesventas` module
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
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();

        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos,
           /// "numerodoc" => $id,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
           if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $entidades = new ClientesVentas();
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];
                //$entidades->flg_estado = Utils::ACTIVO;
                $entidades->id_usuario_reg = Yii::$app->user->getId();
                $entidades->fecha_reg = Utils::getFechaActual();
                $entidades->ipmaq_reg = Utils::obtenerIP();

                if (!$entidades->save()) {
                    Utils::show($entidades->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $direccion = new Direcciones();
                $direccion->id_entidad = $entidades->id_entidad;
                $direccion->id_ubigeo = $post['ubigeos'];
                $direccion->direccion = $post['direccion'];
                $direccion->urbanizacion = $post['urbanizacion'];
                $direccion->referencias = $post['referencias'];
                $direccion->flg_estado = Utils::ACTIVO;
                $direccion->id_usuario_reg = Yii::$app->user->getId();
                $direccion->fecha_reg = Utils::getFechaActual();
                $direccion->ipmaq_reg = Utils::obtenerIP();

                if (!$direccion->save()) {
                    Utils::show($direccion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($entidades->id_entidad);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entidades->id_entidad;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $entidad = ClientesVentas::find()->where(["fecha_del" => null])->all();
        $data = ClientesVentas::findOne($id);
        $direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $data->id_entidad])->one();

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "entidad" => $data,
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
            "direccion" => $direccion,
            "ubigeos" => $ubigeos,
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
                $entidades = ClientesVentas::findOne($post['id_entidad']);
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];

                $entidades->id_usuario_act = Yii::$app->user->getId();
                $entidades->fecha_act = Utils::getFechaActual();
                $entidades->ipmaq_act = Utils::obtenerIP();

                if (!$entidades->update()) {
                    Utils::show($entidades->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $direccion = Direcciones::findOne($post['id_direccion']);
                $direccion->id_direccion = $post['id_direccion'];
                $direccion->id_entidad = $entidades->id_entidad;
                $direccion->id_ubigeo = $post['ubigeos'];
                $direccion->direccion = $post['direccion'];
                $direccion->urbanizacion = $post['urbanizacion'];
                $direccion->referencias = $post['referencias'];
                $direccion->flg_estado = Utils::ACTIVO;
                $direccion->id_usuario_act = Yii::$app->user->getId();
                $direccion->fecha_act = Utils::getFechaActual();
                $direccion->ipmaq_act = Utils::obtenerIP();

                if (!$direccion->update()) {
                    Utils::show($direccion->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos direccoin");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entidades->id_entidad;


            // echo json_encode($entidades->id_entidad);
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
                $clientesventas = ClientesVentas::findOne($post['id_cliente']);
                $clientesventas->id_usuario_del = Yii::$app->user->getId();
                $clientesventas->fecha_del = Utils::getFechaActual();
                $clientesventas->ipmaq_del = Utils::obtenerIP();

                if (!$clientesventas->save()) {
                    Utils::show($clientesventas->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro clientesventas");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            // echo json_encode($clientesventas->id_producto);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $clientesventas->id_entidad;
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
            $command = Yii::$app->db->createCommand('call listadoClientesVentas(:row,:length,:buscar)');
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
                "documento" => $row['documento'],
                "numero_documento" => $row['numero_documento'],
                "razon_social" => $row['razon_social'],
                "telefono" => $row['telefono'],
                "correo" => $row['correo'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_entidad"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_entidad"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
