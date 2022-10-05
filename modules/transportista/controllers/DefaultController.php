<?php

namespace app\modules\transportista\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Transportista;
use app\models\TipoDocumentos;
use app\models\TipoEntidad;
use app\models\Ubigeos;
use app\models\Direcciones;

/**
 * Default controller for the `transportista` module
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
                            'crear',
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

        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
                    "tipo_entidad" => $tipo_entidad,
                    "tipo_documento" => $tipo_documento,
        ]);
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $transportista = new Transportista();
                $transportista->id_tipo_documento = $post['tipo_documento'];
                $transportista->numero_documento = $post['numero_documento'];
                $transportista->razon_social = $post['razon_social'];
                $transportista->telefono = $post['telefono'];
                $transportista->correo = $post['correo'];
                $transportista->ubigeo = $post['ubigeos'];
                $transportista->direccion = $post['direccion'];
                $transportista->urbanizacion = $post['urbanizacion'];
                $transportista->referencia = $post['referencias'];
                $transportista->flg_estado = Utils::ACTIVO;
                $transportista->id_usuario_reg = Yii::$app->user->getId();
                $transportista->fecha_reg = Utils::getFechaActual();
                $transportista->ipmaq_reg = Utils::obtenerIP();

                if (!$transportista->save()) {
                    Utils::show($transportista->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $transportista->id_transportista;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $data = Transportista::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "entidad" => $data,
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
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
                $transportista = Transportista::findOne($post['id_transportista']);
                $transportista->id_tipo_documento = $post['tipo_documento'];
                $transportista->numero_documento = $post['numero_documento'];
                $transportista->razon_social = $post['razon_social'];
                $transportista->telefono = $post['telefono'];
                $transportista->correo = $post['correo'];
                $transportista->ubigeo = $post['ubigeos'];
                $transportista->direccion = $post['direccion'];
                $transportista->urbanizacion = $post['urbanizacion'];
                $transportista->referencia = $post['referencias'];
                $transportista->id_usuario_act = Yii::$app->user->getId();
                $transportista->fecha_act = Utils::getFechaActual();
                $transportista->ipmaq_act = Utils::obtenerIP();

                if (!$transportista->update()) {
                    Utils::show($transportista->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

            

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $transportista->id_transportista;


            // echo json_encode($transportista->id_transportista);
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
                $transportista = Transportista::findOne($post['id_transportista']);
                $transportista->id_usuario_del = Yii::$app->user->getId();
                $transportista->fecha_del = Utils::getFechaActual();
                $transportista->ipmaq_del = Utils::obtenerIP();

                if (!$transportista->save()) {
                    Utils::show($transportista->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro transportista");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //    echo json_encode($transportista->id_transportista);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $transportista->id_transportista;
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
            $command = Yii::$app->db->createCommand('call listadoTrasnsportista(:row,:length,:buscar)');
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
                "direccion" => $row['direccion'],
                "nombre_distrito" => $row['nombre_distrito'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_transportista"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_transportista"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
