<?php

namespace app\modules\seguimientocliente\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\models\Estados;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use app\models\GuiaRemisionCliente;
use app\modules\seguimientocliente\query\ConsultasG;
use Exception;
use yii\web\HttpException;

/**
 * Default controller for the `guiaremision` module
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
                            'editar',
                            'get-modal-edit-g-c',
                            'updateg',
                            'update',
                            'lista',
                            'guia-cliente'

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

    public function actionEditar($id) {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaSeguimiento(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }


        $estados = Estados::find()->where(["fecha_del" => null])->all();
        $guia_cliente = GuiaRemisionCliente::find()->where(["fecha_del" => null, "id_guia_remision" => $id])->all();
        $data = GuiaRemision::findOne($id);
        return $this->render('editar', [
                    "seguimiento" => $result,
                    "estados" => $estados,
                    "guia_cliente" => $guia_cliente,
                    "guia_remision" => $data
        ]);
    }

    public function actionGetModalEditGC($id) {
        

        $estados = Estados::find()->where(["fecha_del" => null])->all();
        $tipo_estados = Estados::find()->where(["fecha_del" => null])->all();
        $data = GuiaRemisionCliente::findOne($id);
        $tipo_carga = TipoCarga::find()->where(["fecha_del" => null, "id_tipo_carga" => $data->id_tipo_carga])->one();
        $archivo = \app\models\Archivo::findAll($data);
         $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaSeguimientorc(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $plantilla = Yii::$app->controller->renderPartial("editarguiacliente", [
            "guiaRC" => $data,
            "tipo_estados" => $tipo_estados,
            "tipo_carga" => $tipo_carga,
            "estados" => $estados,
            "archivo" => $archivo,
            "seguimientorc" => $result,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdateg() {


        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {     //Traemos los datos mediante el id 
                $guiaRC = GuiaRemisionCliente::findOne($post['id_guia_remision_cliente']);

                $guiaRC->id_estado_cargo = $post['estado_cargo'];
                $guiaRC->recibido_por = $post['recibido_por'];
                $guiaRC->entregado_por = $post['entregado_por'];
                $guiaRC->id_estado_mercaderia = $post['estado_mercaderia'];
                $guiaRC->observacion = $post['obsevacion'];
                $guiaRC->fecha_hora_entrega = $post['fecha_hora_entrega'];
                $guiaRC->fecha_cargo = $post['fecha_cargo'];
                $guiaRC->hora_entrega = $post['hora_entrega'];


                $guiaRC->id_usuario_act = Yii::$app->user->getId();
                $guiaRC->fecha_act = Utils::getFechaActual();
                $guiaRC->ipmaq_act = Utils::obtenerIP();

                if (!$guiaRC->save()) {
                    Utils::show($guiaRC->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           // echo json_encode($guiaRC->id_guia_remision_cliente);
           Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRC->id_guia_remision_cliente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUpdate() {
        $id = $_POST["id_guia_remision"];



        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {     //Traemos los datos mediante el id 
                $seguimiento = GuiaRemision::findOne($id);
                $seguimiento->id_estado = $post['estado'];
                $seguimiento->comentario = $post['comentario'];
                //  $seguimiento->flg_estado = Utils::ACTIVO;
                $seguimiento->id_usuario_reg = Yii::$app->user->getId();
                $seguimiento->fecha_reg = Utils::getFechaActual();
                $seguimiento->ipmaq_reg = Utils::obtenerIP();

                if (!$seguimiento->save()) {
                    Utils::show($seguimiento->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }


Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $seguimiento->id_guia_remision;

            //echo json_encode($seguimiento->id_guia_remision);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $usuario = Yii::$app->user->getId();

        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoGuiaClSeguimiento(:row,:length,:buscar,:usuario_session,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':usuario_session', $usuario);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = [];
        foreach ($result as $k => $row) {

            $botones = ' <a  class="btn btn-icon btn-light-primary mr-2" href="pedidosclientes/default/visualizar/' . $row["id_guia_remision"] . '"><i class="flaticon-eye"></i></a>';

        /*    if ($row["nombre_estado"] == "PENDIENTE") {
                $botones .= ' <a class="btn btn-icon btn-light-success mr-2" href="pedidosclientes/default/editar/' . $row["id_pedido_cliente"] . '"><i class="flaticon-edit"></i></a>
                             <button class="btn btn-icon btn-light-danger  mr-2" onclick="funcionEliminar(' . $row["id_pedido_cliente"] . ')"><i class="flaticon-delete"></i></button>';
            }*/

            $data[] = [
                "nm_solicitud" => $row['nm_solicitud'],
                "numero_guia" => $row['numero_guia'],
                "gr" => $row['gr'],
                "razon_social" => $row['razon_social'],
                "destino" => $row['destino'],
                "nombre_estado" => $row['nombre_estado'],
                "accion" => $botones,
            ];
        }



        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => $perpage,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionGuiaCliente() {
        $id = $_POST["id_guia_remision"];
        $guia_cliente = ConsultasG::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }
 
 

}
