<?php

namespace app\modules\seguridad\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
use app\models\Personas;
use app\models\Perfiles;
use app\models\Areas;
use app\models\Usuarios;

/**
 * Default controller for the `seguridad` module
 */
class UsuarioController extends Controller {

    public $enableCsrfValidation = false;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
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

    public function actionGetModal() {
        $persona = Personas::find()->where(["fecha_del" => null])->all();
        $perfil = Perfiles::find()->where(["fecha_del" => null, "estado" => 1])->all();
        $area = Areas::find()->where(["fecha_del" => null])->all();
        $agente = \app\models\Agente::find()->where(["fecha_del" => null])->all();
        $agencia = \app\models\Agencia::find()->where(["fecha_del" => null])->all();

        $plantilla = Yii::$app->controller->renderPartial("crearUsuario", [
            "persona" => $persona,
            "perfil" => $perfil,
            "area" => $area,
            "agente" => $agente,
            "agencia"=> $agencia
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $usuario = new Usuarios();
                $usuario->id_persona = $post["persona"];
                $usuario->id_area = $post["area"];
                $usuario->id_perfil = $post["perfil"];
                $usuario->id_agente = $post["agente"];
                $usuario->usuario = $post["usuario"];
                $usuario->password = Yii::$app->security->generatePasswordHash($post["password"]);
                $usuario->correo = $post["correo"];
                $usuario->id_usuario_reg = Yii::$app->user->getId();
                $usuario->fecha_reg = Utils::getFechaActual();
                $usuario->ipmaq_reg = Utils::obtenerIP();

                if (!$usuario->save()) {
                    Utils::show($usuario->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Usuario");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($usuario->id_usuario);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $usuario->id_usuario;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $data = Usuarios::findOne($id);
        $persona = Personas::find()->where(["fecha_del" => null])->all();
        $perfil = Perfiles::find()->where(["fecha_del" => null, "estado" => 1])->all();
        $area = Areas::find()->where(["fecha_del" => null])->all();
        $agente = \app\models\Agente::find()->where(["fecha_del" => null])->all();
        $agencia = \app\models\Agencia::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("editarUsuario", [
            "usuario" => $data,
            "persona" => $persona,
            "perfil" => $perfil,
            "area" => $area,
            "agente" => $agente,
            "agencia"=>$agencia
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $usuario = Usuarios::findOne($post['id_usuario']);
                $usuario->id_persona = $post["persona"];
                $usuario->id_area = $post["area"];
                $usuario->id_perfil = $post["perfil"];
                $usuario->usuario = $post["usuario"];
                $usuario->id_agente = $post["agente"];
               
                if (!empty($post["password"])) {
                    $usuario->password = Yii::$app->security->generatePasswordHash($post["password"]);
                }
                $usuario->correo = $post["correo"];
                $usuario->id_usuario_act = Yii::$app->user->getId();
                $usuario->fecha_act = Utils::getFechaActual();
                $usuario->ipmaq_act = Utils::obtenerIP();

                if (!$usuario->update()) {
                    Utils::show($usuario->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos usuario");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($usuario->id_usuario);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $usuario->id_usuario;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                $usuario = Usuarios::findOne($post['id_usuario']);
                $usuario->id_usuario_del = Yii::$app->user->getId();
                $usuario->estado = Utils::INACTIVO;
                $usuario->fecha_del = Utils::getFechaActual();
                $usuario->ipmaq_del = Utils::obtenerIP();

                if (!$usuario->save()) {
                    Utils::show($usuario->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro usuario");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            // echo json_encode($usuario->id_usuario);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $usuario->id_usuario;
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
            $command = Yii::$app->db->createCommand('call listadoUsuario(:row,:length,:buscar)');
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
                "usuario" => $row['usuario'],
                "perfil" => $row['perfil'],
                "area" => $row['area'],
                "persona" => $row['persona'],
                "accion" => '<button class="btn  btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditarUsuario(' . $row["id_usuario"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn  btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminarUsuario(' . $row["id_usuario"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
