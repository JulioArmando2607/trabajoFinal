<?php

namespace app\modules\vehiculos\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Vehiculos;
use app\models\Entidades;
use app\models\Ubigeos;
use yii\filters\AccessControl;

/**
 * Default controller for the `vehiculos` module
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
                            'crear',
                            'create',
                            'get-modal-edit',
                            'get-modal-reglubr',
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

        $marcavehiculos = \app\models\MarcaVehiculo::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "marcavehiculos" => $marcavehiculos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {
        $marcavehiculos = \app\models\MarcaVehiculo::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
                    "marcavehiculos" => $marcavehiculos,
        ]);
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $vehiculos = new Vehiculos();
                $vehiculos->id_marca = $post['marca_vehiculo'];
                $vehiculos->placa = $post['placa'];
                $vehiculos->descripcion = $post['descripcion'];
                $vehiculos->incripcion = $post['incripcion'];
                $vehiculos->config_vehicular = $post['config_vehicular'];
                $vehiculos->flg_estado = Utils::ACTIVO;
                $vehiculos->id_usuario_reg = Yii::$app->user->getId();
                $vehiculos->fecha_reg = Utils::getFechaActual();
                $vehiculos->ipmaq_reg = Utils::obtenerIP();

                if (!$vehiculos->save()) {
                    Utils::show($vehiculos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($vehiculos->id_direccion);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $vehiculos->id_vehiculo;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {

        $marcavehiculos = \app\models\MarcaVehiculo::find()->where(["fecha_del" => null])->all();
        $data = Vehiculos::findOne($id);

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "vehiculos" => $data,
            "marcavehiculos" => $marcavehiculos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }
   public function actionGetModalReglubr($id) {

        $marcavehiculos = \app\models\MarcaVehiculo::find()->where(["fecha_del" => null])->all();
        $data = Vehiculos::findOne($id);

        $plantilla = Yii::$app->controller->renderPartial("registar_preventivo", [
            "vehiculos" => $data,
            "marcavehiculos" => $marcavehiculos,
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
                $vehiculos = Vehiculos::findOne($post['id_vehiculo']);
                $vehiculos->id_marca = $post['marca_vehiculo'];
                $vehiculos->placa = $post['placa'];
                $vehiculos->descripcion = $post['descripcion'];
                $vehiculos->incripcion = $post['incripcion'];
                $vehiculos->config_vehicular = $post['config_vehicular'];                
                $vehiculos->id_usuario_act = Yii::$app->user->getId();
                $vehiculos->fecha_act = Utils::getFechaActual();
                $vehiculos->ipmaq_act = Utils::obtenerIP();

                if (!$vehiculos->save()) {
                    Utils::show($vehiculos->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $vehiculos->id_vehiculo;
 
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
                $vehiculos = Vehiculos::findOne($post['id_vehiculo']);
                $vehiculos->id_usuario_del = Yii::$app->user->getId();
                $vehiculos->fecha_del = Utils::getFechaActual();
                $vehiculos->ipmaq_del = Utils::obtenerIP();

                if (!$vehiculos->save()) {
                    Utils::show($vehiculos->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro vehiculos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //  echo json_encode($vehiculos->id_direccion);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $vehiculos->id_vehiculo;
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
            $command = Yii::$app->db->createCommand('call listadoVehiculos(:row,:length,:buscar)');
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
                "placa" => $row['placa'],
                "descripcion" => $row['descripcion'],
                "incripcion" => $row['incripcion'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_vehiculo"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button title="Registrar uso preventivo de lubricante" class="btn btn-sm btn-warning font-weight-bold mr-2" onclick="funcionRegPrevLubricante(' . $row["id_vehiculo"] . ')"><i class="flaticon-car"></i></button> 
                             <button title="Eliminar" class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_vehiculo"] . ')"><i class="flaticon-delete"></i></button>',
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
