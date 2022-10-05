<?php

namespace app\modules\procesarguias\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\procesarguias\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use app\models\DetalleGuiaRemitente;
use app\models\GuiaRemisionCliente;
 

class DefaultController extends Controller {
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'crear',
                            'buscar-tipo-v',
                            'buscar-guia',
                            'buscar-direccion',
                            'listar-pend-guias',
                            'ultima-guia',
                            'procesar',
                            'create',
                            'editar',
                            'detalle-guia',
                            'guia-cliente',
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
           $result = null;
        try {

            $command = Yii::$app->db->createCommand('call selectNumeroSolicitud(:usuario_sesion)');
            $command->bindValue(':usuario_sesion', Yii::$app->user->getId());
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        
        
        return $this->render('index',[
             "result" => $result,
        ]);
    }

    public function actionCrear() {
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $via_->id_via])->all();
        $via = Via::find()->where(["fecha_del" => null])->all();
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
                    "via" => $via,
                    "rem_des_client" => $rem_des_client,
                    "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "via_tipo" => $via_tipo,
                    "transportista" => $transportista
        ]);
    }

    public function actionBuscarTipoV() {
        $id_via = $_POST["id_via"];

        $via_ = Via::find()->where(["fecha_del" => null])->one();

        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $id_via])->all();

        $data = "";
        foreach ($via_tipo as $d) {

            $data .= '<option value="' . $d["id_tipo_via_carga"] . '">' . $d->tipo_via_carga . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarGuia() {
        $numerog = $_POST["numero"];
        $serie = $_POST["serie"];

        $result = null;
        try {

            $command = Yii::$app->db->createCommand('call consultaNumeroGuia(:numero_guia,:serie_guia)');
            $command->bindValue(':numero_guia', $numerog);
            $command->bindValue(':serie_guia', $serie);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionBuscarDireccion() {
        $id_entidad = $_POST["id_entidad"];
        $direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $id_entidad])->all();

        $data = "";
        foreach ($direccion as $d) {
            $data .= '<option value="' . $d->id_direccion . '">' . $d->direccion . ' ' . $d->urbanizacion . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionListarPendGuias() {
        $result = null;
        try {

            $command = Yii::$app->db->createCommand('call selectNumeroSolicitud(:usuario_sesion)');
            $command->bindValue(':usuario_sesion', Yii::$app->user->getId());
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = "";
        foreach ($result as $d) {
            $data .= '<option value="" disabled selected>Seleccione</option> <option value="' . $d['nm_solicitud'] . '">' . $d['nm_solicitud'] . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionUltimaGuia() {
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call ultimaGuia()');
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = 0;
        foreach ($result as $vala) {
            $data = $vala;
        }
        
         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $data;
    }

    public function actionProcesar() {
        // obtienes el cuerpo del POST
        $param = json_decode($_REQUEST['selected']);
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call ultimaGuia()');
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = 0;
        foreach ($result as $vala) {
            $data = $vala;
        }
        foreach ($param as $val) {

            $data = $data + 1;

            try {
                $guiaRemision = GuiaRemision::findOne($val);
                $guiaRemision->serie = '0001';

                $guiaRemision->numero_guia = $data;
                $guiaRemision->id_estado = Utils::RECOGIDO;
                $guiaRemision->id_usuario_act = Yii::$app->user->getId();
                $guiaRemision->fecha_act = Utils::getFechaActual();
                $guiaRemision->ipmaq_act = Utils::obtenerIP();

                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        }
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = new GuiaRemision();
                $guiaRemision->serie = $post["serie"];
                $guiaRemision->numero_guia = $post["numero"];
                $guiaRemision->fecha = $post["fecha"];
                $guiaRemision->fecha_traslado = $post["traslado"];
                $guiaRemision->id_via = $post["via"];
                $guiaRemision->id_tipo_via = $post["via_tipo"];
                $guiaRemision->id_cliente = $post["cliente"];
                $guiaRemision->id_agente = $post["agente"];
                $guiaRemision->id_remitente = $post["remitente"];
                $guiaRemision->id_direccion_partida = $post["direccion_partida"];
                $guiaRemision->id_destinatario = $post["destinatario"];
                $guiaRemision->id_direccion_llegada = $post["direccion_llegada"];
                $guiaRemision->id_conductor = $post["conductor"];
                $guiaRemision->id_vehiculo = $post["vehiculo"];
                $guiaRemision->transportista = $post["transportista"];
                $guiaRemision->guia_remision_transportista = $post["guia_remision"];
                $guiaRemision->factura_transportista = $post["factura"];
                $guiaRemision->importe_transportista = $post["importe"];
                $guiaRemision->comentario_transportista = $post["comentario"];
                $guiaRemision->id_estado = Utils::RECOGIDO;
                $guiaRemision->id_usuario_reg = Yii::$app->user->getId();
                $guiaRemision->fecha_reg = Utils::getFechaActual();
                $guiaRemision->ipmaq_reg = Utils::obtenerIP();

                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $detalle_guia = empty($post["detalle_guia"]) ? [] : $post["detalle_guia"];

                foreach ($detalle_guia as $dg) {
                    $detalleGuia = new DetalleGuiaRemitente();
                    $detalleGuia->id_guia_remision = $guiaRemision->id_guia_remision;
                    $detalleGuia->id_producto = $dg["id_producto"];
                    $detalleGuia->cantidad = $dg["cantidad"];
                    $detalleGuia->peso = $dg["peso"];
                    $detalleGuia->volumen = $dg["volumen"];
                    $detalleGuia->alto = $dg["alto"];
                    $detalleGuia->ancho = $dg["ancho"];
                    $detalleGuia->largo = $dg["largo"];
                    $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
                    $detalleGuia->fecha_reg = Utils::getFechaActual();
                    $detalleGuia->ipmaq_reg = Utils::obtenerIP();

                    if (!$detalleGuia->save()) {
                        Utils::show($detalleGuia->getErrors(), true);
                        throw new HttpException("No se puede guardar datos detalle guia");
                    }
                }

                $detalle_guia_rc = empty($post["detalle_guia_rc"]) ? [] : $post["detalle_guia_rc"];

                foreach ($detalle_guia_rc as $dgr) {
                    $guia_remision_cliente = new GuiaRemisionCliente();
                    $guia_remision_cliente->id_guia_remision = $guiaRemision->id_guia_remision;
                    $guia_remision_cliente->grs = $dgr["grs"];
                    $guia_remision_cliente->gr = $dgr["gr"];
                    $guia_remision_cliente->ft = $dgr["ft"];
                    $guia_remision_cliente->oc = $dgr["oc"];
                    $guia_remision_cliente->id_tipo_carga = $dgr["id_tipo_carga"];
                    $guia_remision_cliente->descripcion = $dgr["descripcion"];
                    $guia_remision_cliente->id_estado_mercaderia = Utils::RECOGIDO;
                    $guia_remision_cliente->id_usuario_reg = Yii::$app->user->getId();
                    $guia_remision_cliente->fecha_reg = Utils::getFechaActual();
                    $guia_remision_cliente->ipmaq_reg = Utils::obtenerIP();

                    if (!$guia_remision_cliente->save()) {
                        Utils::show($guia_remision_cliente->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision cliente");
                    }
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditar($id) {

        $guia = GuiaRemision::findOne($id);
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $guia->id_via])->all();
        $via = Via::find()->where(["fecha_del" => null])->all();
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();

        return $this->render('editar', [
                    "guia" => $guia,
                    "via" => $via,
                    "rem_des_client" => $rem_des_client,
                    "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "via_tipo" => $via_tipo,
                    "transportista" => $transportista
        ]);
    }

    public function actionDetalleGuia() {
        $id = $_POST["id_guia"];
        $detalle_guia = Consultas::getDetalleGuia($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public function actionGuiaCliente() {
        $id = $_POST["id_guia"];
        $guia_cliente = Consultas::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = GuiaRemision::findOne($post["id_guia"]);
                $guiaRemision->fecha = $post["fecha"];
                $guiaRemision->fecha_traslado = $post["traslado"];
                $guiaRemision->id_via = $post["via"];
                $guiaRemision->id_tipo_via = $post["via_tipo"];
                $guiaRemision->id_cliente = $post["cliente"];
                $guiaRemision->id_agente = $post["agente"];
                $guiaRemision->id_remitente = $post["remitente"];
                $guiaRemision->id_direccion_partida = $post["direccion_partida"];
                $guiaRemision->id_destinatario = $post["destinatario"];
                $guiaRemision->id_direccion_llegada = $post["direccion_llegada"];
                $guiaRemision->id_conductor = $post["conductor"];
                $guiaRemision->id_vehiculo = $post["vehiculo"];
                $guiaRemision->transportista = $post["transportista"];
                $guiaRemision->guia_remision_transportista = $post["guia_remision"];
                $guiaRemision->factura_transportista = $post["factura"];
                $guiaRemision->importe_transportista = $post["importe"];
                $guiaRemision->comentario_transportista = $post["comentario"];
                $guiaRemision->id_usuario_act = Yii::$app->user->getId();
                $guiaRemision->fecha_act = Utils::getFechaActual();
                $guiaRemision->ipmaq_act = Utils::obtenerIP();

                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $detalle_guia = empty($post["detalle_guia_edit"]) ? [] : $post["detalle_guia_edit"];

                foreach ($detalle_guia as $dg) {
                    if ($dg["flg"] == 1) {
                        $detalleGuia = new DetalleGuiaRemitente();
                        $detalleGuia->id_guia_remision = $guiaRemision->id_guia_remision;
                        $detalleGuia->id_producto = $dg["id_producto"];
                        $detalleGuia->cantidad = $dg["cantidad"];
                        $detalleGuia->peso = $dg["peso"];
                        $detalleGuia->volumen = $dg["volumen"];
                        $detalleGuia->alto = $dg["alto"];
                        $detalleGuia->ancho = $dg["ancho"];
                        $detalleGuia->largo = $dg["largo"];
                        $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
                        $detalleGuia->fecha_reg = Utils::getFechaActual();
                        $detalleGuia->ipmaq_reg = Utils::obtenerIP();

                        if (!$detalleGuia->save()) {
                            Utils::show($detalleGuia->getErrors(), true);
                            throw new HttpException("No se puede guardar datos detalle guia");
                        }
                    }
                }

                $detalle_guia_rc = empty($post["detalle_guia_edit_rc"]) ? [] : $post["detalle_guia_edit_rc"];

                foreach ($detalle_guia_rc as $dgr) {
                    if ($dgr["flg"] == 1) {
                        $guia_remision_cliente = new GuiaRemisionCliente();
                        $guia_remision_cliente->id_guia_remision = $guiaRemision->id_guia_remision;
                        $guia_remision_cliente->grs = $dgr["grs"];
                        $guia_remision_cliente->gr = $dgr["gr"];
                        $guia_remision_cliente->ft = $dgr["ft"];
                        $guia_remision_cliente->oc = $dgr["oc"];
                        $guia_remision_cliente->id_tipo_carga = $dgr["id_tipo_carga"];
                        $guia_remision_cliente->descripcion = $dgr["descripcion"];
                        $guia_remision_cliente->id_usuario_reg = Yii::$app->user->getId();
                        $guia_remision_cliente->fecha_reg = Utils::getFechaActual();
                        $guia_remision_cliente->ipmaq_reg = Utils::obtenerIP();

                        if (!$guia_remision_cliente->save()) {
                            Utils::show($guia_remision_cliente->getErrors(), true);
                            throw new HttpException("No se puede guardar datos guia remision cliente");
                        }
                    }
                }

                $detalle_guia_delete = empty($post["detalle_guia_delete"]) ? [] : $post["detalle_guia_delete"];

                foreach ($detalle_guia_delete as $dg) {
                    if ($dg["flg"] == 0) {
                        $detalleGuia = DetalleGuiaRemitente::findOne($dg["identificadorDetalle"]);
                        $detalleGuia->id_usuario_del = Yii::$app->user->getId();
                        $detalleGuia->fecha_del = Utils::getFechaActual();
                        $detalleGuia->ipmaq_del = Utils::obtenerIP();

                        if (!$detalleGuia->save()) {
                            Utils::show($detalleGuia->getErrors(), true);
                            throw new HttpException("No se puede guardar datos detalle guia");
                        }
                    }
                }

                $detalle_guia_delete_rc = empty($post["detalle_guia_delete_rc"]) ? [] : $post["detalle_guia_delete_rc"];

                foreach ($detalle_guia_delete_rc as $dgr) {
                    if ($dgr["flg"] == 0) {
                        $guia_remision_cliente = GuiaRemisionCliente::findOne($dgr["identificadorDetalle"]);
                        $guia_remision_cliente->id_usuario_del = Yii::$app->user->getId();
                        $guia_remision_cliente->fecha_del = Utils::getFechaActual();
                        $guia_remision_cliente->ipmaq_del = Utils::obtenerIP();

                        if (!$guia_remision_cliente->save()) {
                            Utils::show($guia_remision_cliente->getErrors(), true);
                            throw new HttpException("No se puede guardar datos guia remision cliente");
                        }
                    }
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = GuiaRemision::findOne($post["id_guia"]);
                $guiaRemision->id_usuario_del = Yii::$app->user->getId();
                $guiaRemision->fecha_del = Utils::getFechaActual();
                $guiaRemision->ipmaq_del = Utils::obtenerIP();

                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
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

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoGuiasPendiente(:row,:length,:buscar,@total, :usuario_sesion)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':usuario_sesion', Yii::$app->user->getId());
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $check = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank"><input type="checkbox" id="cbox2" value=' . $row["id_guia_remision"] . ' name="page" ></a>';
            $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="procesarguias/default/editar/' . $row["id_guia_remision"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["nombre_estado"] == "PENDIENTE_GUIA") {
                $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminarGuia(' . $row["id_guia_remision"] . ')"><i class="flaticon-delete"></i></button>';
            }            
             

            $data[] = [
                "nm_solicitud" => $row['nm_solicitud'],
                "fecha" => $row['fecha'],
                "fecha_traslado" => $row['fecha_traslado'],
                "origen" => $row['origen'],
                "destino" => $row['destino'],
                "nombre_estado" => $row['nombre_estado'],
                "remitente" => $row['remitente'],
                "destinatario" => $row['destinatario'],
                "accion" => $botones,
                "check" => $check
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

}
