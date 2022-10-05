<?php

namespace app\modules\persona\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Personas;
use yii\filters\AccessControl;

/**
 * Default controller for the `persona` module
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
                            'buscar-numero-doc'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGetModal() {
        $entidad = \app\models\Entidades::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "entidad" => $entidad,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $persona = new Personas();
                $persona->dni = $post['dni'];
                $persona->nombres = $post['nombres'];
                $persona->apellido_paterno = $post['apellido_paterno'];
                $persona->apellido_materno = $post['apellido_materno'];
                $persona->id_entidad = $post['entidad'];
                $persona->id_sexo = $post['sexo'];
                $persona->flg_conductor = $post['conductor'];
                $persona->telefono = $post['telefono'];
                $persona->correo = $post['correo'];
                $persona->fecha_nacimiento = $post['fecha_nacimiento'];
                $persona->id_usuario_reg = Yii::$app->user->getId();
                $persona->fecha_reg = Utils::getFechaActual();
                $persona->ipmaq_reg = Utils::obtenerIP();

                if (!$persona->save()) {
                    Utils::show($persona->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }
                if ($post['conductor'] == 1) {
                    $empleado = new \app\models\Empleados();
                    $empleado->id_persona = $persona->id_persona;
                    $empleado->flg_conductor = $post['conductor'];
                    $empleado->licencia = $post['licencia'];
                    $empleado->flg_auxiliar = $post['conductor'];

                    $empleado->id_usuario_reg = Yii::$app->user->getId();
                    $empleado->fecha_reg = Utils::getFechaActual();
                    $empleado->ipmaq_reg = Utils::obtenerIP();
                    if (!$empleado->save()) {
                        Utils::show($empleado->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($persona->id_persona);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $persona->id_persona;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Personas::findOne($id);
        $entidad = \app\models\Entidades::find()->where(["fecha_del" => null])->all();
        $empleados = \app\models\Empleados::find()->where(["fecha_del" => null, "id_persona" => $id])->one();
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "persona" => $data,
            "entidad" => $entidad,
            "empleados" => $empleados
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
                $persona = Personas::findOne($post['id_persona']);
                $persona->dni = $post['dni'];
                $persona->nombres = $post['nombres'];
                $persona->apellido_paterno = $post['apellido_paterno'];
                $persona->apellido_materno = $post['apellido_materno'];
                $persona->id_sexo = $post['sexo'];
                $persona->id_entidad = $post['entidad'];
             
                $persona->flg_conductor = $post['conductor'];
                $persona->telefono = $post['telefono'];
                $persona->correo = $post['correo'];
                $persona->fecha_nacimiento = $post['fecha_nacimiento'];
                $persona->id_usuario_act = Yii::$app->user->getId();
                $persona->fecha_act = Utils::getFechaActual();
                $persona->ipmaq_act = Utils::obtenerIP();

                if (!$persona->save()) {
                    Utils::show($persona->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                  if ($post['conductor'] == 1) {
                    $empleado =\app\models\Empleados::find()->where(["fecha_del" => null, "id_persona" =>  $post['id_persona']])->one();
                    $empleado->id_persona = $persona->id_persona;
                    $empleado->flg_conductor = $post['conductor'];
                    $empleado->licencia = $post['licencia'];
                    $empleado->flg_auxiliar = $post['conductor'];

                    $empleado->id_usuario_act= Yii::$app->user->getId();
                    $empleado->fecha_act = Utils::getFechaActual();
                    $empleado->ipmaq_act = Utils::obtenerIP();
                    
                    if (!$empleado->save()) {
                        Utils::show($empleado->getErrors(), true);
                        throw new HttpException("No se puede guardar datos Persona");
                    }
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($persona->id_persona);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $persona->id_persona;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionBuscarNumeroDoc() {
        $numerog = $_POST["numero"];

        $result = null;
        try {

            $command = Yii::$app->db->createCommand('call consultaNumeroDocumentoPersona(:numero_documento)');
            $command->bindValue(':numero_documento', $numerog);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $persona = Personas::findOne($post['id_persona']);
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
            //echo json_encode($persona->id_persona);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $persona->id_persona;
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
            $command = Yii::$app->db->createCommand('call listadoPersona(:row,:length,:buscar)');
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
                "dni" => $row['dni'],
                "nombres" => $row['nombres'],
                "apellido_paterno" => $row['apellido_paterno'],
                "apellido_materno" => $row['apellido_materno'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_persona"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_persona"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
