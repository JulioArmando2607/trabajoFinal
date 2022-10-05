<?php

namespace app\modules\guiaremision\controllers;

use app\models\Personas;
use app\models\User;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\guiaremision\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use app\models\DetalleGuiaRemitente;
use app\models\GuiaRemisionCliente;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * Default controller for the `guiaremision` module
 */
class DefaultController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'reg-agente',
                            'reg-entidad',
                            'crear-entidad',
                            'crear',
                            'buscar-tipo-v',
                            'buscar-guia',
                            'buscar-direccion',
                            'buscar-numero-doc',
                            'create',
                            'editar',
                            'detalle-guia',
                            'guia-cliente',
                            'update',
                            'delete',
                            'lista',
                            'imprimir',
                            'exportar',
                            'imprimirv',
                            'buscar-documento',
                            'exportar-mes',
                            'solicitar-permiso'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegAgente()
    {
        $plantilla = Yii::$app->controller->renderPartial("crearAgente", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionRegEntidad()
    {

        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();

        $plantilla = Yii::$app->controller->renderPartial("crearE", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrearEntidad()
    {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                $entidades = new Entidades();
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento_entidad'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];

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

            Utils::jsonEncode($entidades->id_entidad);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCrear()
    {
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        $via = Via::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $via_->id_via])->all();

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

    public function actionBuscarTipoV()
    {
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

    public function actionBuscarGuia()
    {
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

    public function actionBuscarDireccion()
    {
        $id_entidad = $_POST["id_entidad"];
        $direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $id_entidad])->all();

        $data = "";
        foreach ($direccion as $d) {
            $data .= '<option value="' . $d->id_direccion . '">' . $d->direccion . ' ' . $d->urbanizacion . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarNumeroDoc()
    {
        $numerog = $_POST["numero"];

        $result = null;
        try {

            $command = Yii::$app->db->createCommand('call consultaNumeroDocumento(:numero_documento)');
            $command->bindValue(':numero_documento', $numerog);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionBuscarDocumento()
    {
        $numero_documento = $_POST["numero_documento"];
        $tipo_documento = $_POST["tipo_documento"];
        //     print_r($tipoDocumento)
        $tipodc = null;

        if ($tipo_documento == 1) {

            $tipodc = 'dni';
        } else if ($tipo_documento == 2) {
            $tipodc = 'ruc';
        }

        $docservico = Utils::getConsultaDocumento($tipodc, $numero_documento);
        //   print_r($tipodc);
        // die();
        //   echo $docservico;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $docservico;
    }

    public function actionCreate()
    {
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
                    $guia_remision_cliente->cantidad = $dgr["cantidad"];
                    $guia_remision_cliente->peso = $dgr["peso"];
                    $guia_remision_cliente->volumen = $dgr["volumen"];
                    $guia_remision_cliente->alto = $dgr["alto"];
                    $guia_remision_cliente->ancho = $dgr["ancho"];
                    $guia_remision_cliente->largo = $dgr["largo"];
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

    public function actionEditar($id)
    {
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $guia = GuiaRemision::findOne($id);
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $guia->id_via])->all();
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
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
            "transportista" => $transportista,
            "via_tipo" => $via_tipo
        ]);
    }

    public function actionDetalleGuia()
    {
        $id = $_POST["id_guia"];
        $detalle_guia = Consultas::getDetalleGuia($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public function actionGuiaCliente()
    {
        $id = $_POST["id_guia"];
        $guia_cliente = Consultas::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = GuiaRemision::findOne($post["id_guia"]);
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
//                $guiaRemision->id_estado = Utils::PENDIENTE;
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
                        $guia_remision_cliente->cantidad = $dgr["cantidad"];
                        $guia_remision_cliente->peso = $dgr["peso"];
                        $guia_remision_cliente->volumen = $dgr["volumen"];
                        $guia_remision_cliente->alto = $dgr["alto"];
                        $guia_remision_cliente->ancho = $dgr["ancho"];
                        $guia_remision_cliente->largo = $dgr["largo"];
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

    public function actionDelete()
    {
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

    public function actionLista()
    {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        // $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoGuiaRemision(:row,:length,:buscar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="guiaremision/default/imprimirv/' . $row["id_guia_remision"] . '"><i class="icon-2x flaticon-doc"></i></a>'
                . '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="guiaremision/default/imprimir/' . $row["id_guia_remision"] . '"><i class="flaticon2-print"></i></a>';

            if ($row["flg_guia"] == 1) {
                $botones .= '<a class="btn btn-icon btn-light-primary btn-sm mr-2" onclick="funcionSoicitarPermiso(' . $row["id_guia_remision"] . ')"><i class="flaticon-lock"></i></a>';
            }else{
                $botones .= '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="guiaremision/default/editar/' . $row["id_guia_remision"] . '"><i class="flaticon-edit"></i></a>';
                $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminarGuia(' . $row["id_guia_remision"] . ')"><i class="flaticon-delete"></i></button>';
            }

            $data[] = [
                "nm_solicitud" => $row['nm_solicitud'],
                "numero_guia" => $row['numero_guia'],
                "fecha" => $row['fecha'],
                "fecha_traslado" => $row['fecha_traslado'],
                "origen" => $row['origen'],
                "destino" => $row['destino'],
                "nombre_estado" => $row['nombre_estado'],
                "cliente" => $row['cliente'],
                "remitente" => $row['remitente'],
                "destinatario" => $row['destinatario'],
                "usuario" => $row['usuario'],
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

    public function actionImprimir($id)
    {

        $data = Consultas::getImprimirGuia($id);

        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 9);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->Ln(34);


        $pdf->Cell(25);
        $pdf->Cell(40, 5, $data["fecha"], 0, 0, 'L');
        $pdf->Cell(20);
        $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');
        $pdf->Cell(30);
        $pdf->Cell(0, -10, $data["numero_guia"], 0, 0, 'L');
        $pdf->Ln(8);


        $pdf->setY(55.0);
        $pdf->setX(20);
        /// $pdf->Cell(20);
        $pdf->MultiCell(75, 3, utf8_decode($data["remitente"]), '', 'J', 0);
        // $pdf->Cell(75, 5, $data["remitente"], 0, 0, 'L');
        // $pdf->Cell(10);
        $pdf->setY(55.0);
        $pdf->setX(120);
        $pdf->MultiCell(75, 3, utf8_decode($data["destinatario"]), '', 'L', 0);
        //$pdf->Cell(70, 5, $data["destinatario"], 0, 0, 'R');
        $pdf->setY(58.0);
        $pdf->Ln();

        $pdf->Cell(15.5);
        $pdf->Cell(80, 6, utf8_decode($data["doc_remitente"]), 0, 0, 'L');
        $pdf->Cell(28);
        $pdf->Cell(85, 6, utf8_decode($data["doc_destinatario"]), 0, 0, 'L');


        $pdf->Ln(9);

        // $pdf->setX(25);
        // $pdf->Cell(10);
        $pdf->setY(68);
        $pdf->setX(19.5);
        $pdf->MultiCell(75, 3, utf8_decode($data["direccion_remitente"]), '', 'R', 0);
        //   $pdf->Cell(100, 9, $data["direccion_remitente"], 0, 0, 'L');
        //   $pdf->Cell(16);
        $pdf->setY(68);
        $pdf->setX(130);
        $pdf->MultiCell(75, 3, utf8_decode($data["direccion_destinatario"]), '', 'L', 0);
        //  $pdf->Cell(95, 8, $data["direccion_destinatario"], 0, 0, 'L');

        $pdf->Ln(3.5);
        $pdf->setY(78);
        $pdf->Cell(83, 11, utf8_decode($data["conductor"]), 0, 0, 'R');
        $pdf->Cell(20);
        $pdf->Cell(80, 13, $data["licencia"], 0, 0, 'R');

        $pdf->Ln(9);

        $pdf->Cell(80, 6, $data["marca"], 0, 0, 'R');
        $pdf->Cell(20);
        $pdf->Cell(80, 6, $data["placa"], 0, 0, 'R');
        $pdf->Ln(5);

        $pdf->Cell(80, 9, $data["incripcion"], 0, 0, 'R');
        $pdf->Cell(20);
        $pdf->Cell(80, 9, $data["config_vehicular"], 0, 0, 'R');
        $pdf->Ln(3);

        /*   $guiaDetalle = Consultas::getDetalleGuia($id);


          foreach ($guiaDetalle as $gd) {

          $pdf->Cell(17, 5, $gd["cantidad"], 0, 0, 'C');
          $pdf->Cell(20, 5, $gd["unidad_medida"], 0, 0, 'C');
          $pdf->Cell(140, 5, 'PESO: ' . $gd["peso"], 0, 0, 'L');
          $pdf->Cell(-120, 5, 'Volumen: ' . $gd["volumen"], 0, 0, 'C');

          $pdf->Ln();
          }

          $Cadena = "";
          $guiaCliente = Consultas::getGuiaCliente($id);
          foreach ($guiaCliente as $gc) {
          $Cadena .= $gc["grs"] .'-'.$gc["gr"] .', ';
          }

          $pdf->Cell(190, 5, 'S.G/R: ' . $Cadena, 0, 0, 'R');

          $pdf->Ln();
          $Cadena2 = "";
          $guiaCliente2 = Consultas::getGuiaCliente($id);
          $valor=1;
          foreach ($guiaCliente2 as $gc) {
          $Cadena2 .= ' FAC:'.$gc["ft"]. ', ';

          if($gc["ft"] !=''){
          $valor=10;
          } else {$valor=1;}
          }
          $pdf->SetFont('ARIAL', 'B', $valor);
          $pdf->Cell(190, 5, '' . $Cadena2, 0, 0, 'R');

          $pdf->Ln();
          $pdf->SetFont('ARIAL', 'B', 10);
          $pdf->setX(50);
          $pdf->Cell(100, 20, utf8_decode("N° PEDIDO: ").$data["solicitudcl"], 0, 0, 'L');
          $pdf->Output();
         */

        $pdf->Ln(2);
        $header = ['', '', ''];
        //  $pdf->SetFillColor( 0,0,0);
        //$pdf->SetTextColor(0);
        //  $pdf->SetDrawColor(70, 88, 115);
        //$pdf->SetLineWidth(.0);
        //  $pdf->SetFont('', '');
        // Header
        $w = array(11, 20, 90);
        $pdf->Cell(6);
        for ($i = 0; $i < count($header); $i++)
            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, 'C', false);
        $pdf->Ln();
        // Color and font restoration
        // $pdf->SetFillColor(156, 180, 203);
        //    $pdf->SetTextColor(0);
        //   $pdf->SetFont('');
        // Data
        $fill = false;

        $result2 = [];
        try {

            $command = Yii::$app->db->createCommand('call consultGcDetalle(:idguia)');
            $command->bindValue(':idguia', $id);
            $result2 = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        foreach ($result2 as $row) {
            $pdf->SetFont('Courier', 'B', 9);
            $pdf->Cell(3);
            $pdf->Cell($w[0], 3, $row["cantidad_detalle"], '', 0, 'L', $fill);
            $pdf->Cell($w[1], 3, $row["unidad_medida"], '', 0, 'L', $fill);
            $pdf->Cell($w[2], 3, "GuiaCl: " . $row["guia_cliente"] . " Peso: " . $row["peso"] . " Volumen: " . $row["volumen"] . " Descr: " . $row["descripcion"], '', 0, 'L', $fill);
            $pdf->Ln();
            //$fill = !$fill;
        }

        // Closing line
        $pdf->Cell(6);
        $pdf->Cell(array_sum($w), 0, '', '');
        $pdf->Ln();
        $pdf->SetFont('Courier', 'B', 9);

        $pdf->setY(100);
        $pdf->setX(142);
        $pdf->Cell(90, 2, utf8_decode("N°.PEDIDO: ") . $data["solicitudcl"], 0, 0, 'L');
        $pdf->Output();
    }

    /* public function actionImprimir($id) {

      $data = Consultas::getImprimirGuia($id);

      $pdf = new \FPDF();
      $pdf->AddPage('P', 'A4');
      $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
      $pdf->SetFont('ARIAL', 'B', 9);
      $pdf->SetAutoPageBreak(true, 10);

      $pdf->Ln(42);


      $pdf->Cell(25);
      $pdf->Cell(40, 5, $data["fecha"], 0, 0, 'L');
      $pdf->Cell(20);
      $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');
      $pdf->Cell(30);
      $pdf->Cell(0, -10, $data["numero_guia"], 0, 0, 'L');
      $pdf->Ln(10);


      $pdf->setY(62.0);
      $pdf->setX(17);
      /// $pdf->Cell(20);
      $pdf->MultiCell(75, 3, utf8_decode($data["remitente"]), '', 'J', 0);
      // $pdf->Cell(75, 5, $data["remitente"], 0, 0, 'L');
      // $pdf->Cell(10);
      $pdf->setY(62.0);
      $pdf->setX(120);
      $pdf->MultiCell(75, 3, utf8_decode($data["destinatario"]), '', 'L', 0);
      //$pdf->Cell(70, 5, $data["destinatario"], 0, 0, 'R');
      $pdf->setY(64.0);
      $pdf->Ln();

      $pdf->Cell(15.5);
      $pdf->Cell(80, 6, $data["doc_remitente"], 0, 0, 'L');
      $pdf->Cell(28);
      $pdf->Cell(85, 6, $data["doc_destinatario"], 0, 0, 'L');


      $pdf->Ln(9);

      // $pdf->setX(25);
      // $pdf->Cell(10);
      $pdf->setY(75.5);
      $pdf->setX(19.5);
      $pdf->MultiCell(75, 3, utf8_decode($data["direccion_remitente"]), '', 'R', 0);
      //   $pdf->Cell(100, 9, $data["direccion_remitente"], 0, 0, 'L');
      //   $pdf->Cell(16);
      $pdf->setY(75.5);
      $pdf->setX(132);
      $pdf->MultiCell(75, 3,utf8_decode($data["direccion_destinatario"]), '', 'L', 0);
      //  $pdf->Cell(95, 8, $data["direccion_destinatario"], 0, 0, 'L');

      $pdf->Ln(3.5);
      $pdf->setY(86);
      $pdf->Cell(80, 11, utf8_decode($data["conductor"]), 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 11, utf8_decode($data["licencia"]), 0, 0, 'R');

      $pdf->Ln(7);

      $pdf->Cell(80, 7, utf8_decode($data["marca"]), 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 6, utf8_decode($data["placa"]), 0, 0, 'R');
      $pdf->Ln(5);

      $pdf->Cell(80, 7, utf8_decode($data["incripcion"]), 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 6, utf8_decode($data["config_vehicular"]), 0, 0, 'R');
      $pdf->Ln(15);

      $guiaDetalle = Consultas::getDetalleGuia($id);
      $pdf->setY(111.5);
      foreach ($guiaDetalle as $gd) {

      $pdf->Cell(6, 5, $gd["cantidad"], 0, 0, 'C');
      $pdf->Cell(23, 5, $gd["unidad_medida"], 0, 0, 'C');
      $pdf->Cell(142, 5, 'PESO: ' . $gd["peso"], 0, 0, 'L');
      $pdf->Cell(-120, 5, 'Volumen: ' . $gd["volumen"], 0, 0, 'C');

      $pdf->Ln();
      }

      $Cadena = "";
      $guiaCliente = Consultas::getGuiaCliente($id);
      foreach ($guiaCliente as $gc) {
      $Cadena .= $gc["grs"]."-" .$gc["gr"] .' FAC:'.$gc["ft"]. ', ';
      }

      $pdf->Cell(180, 5, 'S.G/R: ' . $Cadena, 0, 0, 'R');

      $pdf->Ln();

      $pdf->Output();
      } */
    /*  public function actionImprimir($id) {

      $data = Consultas::getImprimirGuia($id);

      $pdf = new \FPDF();
      $pdf->AddPage('P', 'A4');
      $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
      $pdf->SetFont('ARIAL', 'B', 9);
      $pdf->SetAutoPageBreak(true, 10);

      $pdf->Ln(37);


      $pdf->Cell(25);
      $pdf->Cell(40, 5, $data["fecha"], 0, 0, 'L');
      $pdf->Cell(20);
      $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');

      $pdf->Ln(10);

      $pdf->Cell(20);
      $pdf->Cell(75, 5, $data["remitente"], 0, 0, 'L');
      $pdf->Cell(10);
      $pdf->Cell(70, 5, $data["destinatario"], 0, 0, 'R');

      $pdf->Ln();

      $pdf->Cell(15);
      $pdf->Cell(80, 6, $data["doc_remitente"], 0, 0, 'L');
      $pdf->Cell(28);
      $pdf->Cell(85, 6, $data["doc_destinatario"], 0, 0, 'L');

      $pdf->Ln(10);

      $pdf->Cell(100, 9, $data["direccion_remitente"], 0, 0, 'L');
      $pdf->Cell(16);
      $pdf->Cell(95, 8, $data["direccion_destinatario"], 0, 0, 'L');

      $pdf->Ln(10);

      $pdf->Cell(80, 11, $data["conductor"], 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 11, $data["licencia"], 0, 0, 'R');

      $pdf->Ln(7);

      $pdf->Cell(80, 7, $data["marca"], 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 6, $data["placa"], 0, 0, 'R');

      $pdf->Ln(5);

      $pdf->Cell(80, 7, $data["incripcion"], 0, 0, 'R');
      $pdf->Cell(20);
      $pdf->Cell(80, 6, $data["config_vehicular"], 0, 0, 'R');
      $pdf->Ln(15);
      $guiaDetalle = Consultas::getDetalleGuia($id);
      foreach ($guiaDetalle as $gd) {
      $pdf->Cell(17, 5, $gd["cantidad"], 0, 0, 'C');
      $pdf->Cell(20, 5, $gd["unidad_medida"], 0, 0, 'C');
      $pdf->Cell(140, 5, 'PESO: ' . $gd["peso"], 0, 0, 'L');
      $pdf->Cell(-120, 5, 'Volumen: ' . $gd["volumen"], 0, 0, 'C');
      $pdf->Ln();
      }

      $Cadena = "";
      $guiaCliente = Consultas::getGuiaCliente($id);
      foreach ($guiaCliente as $gc) {
      $Cadena .= $gc["guia_cliente"] . ', ';
      }

      $pdf->Cell(180, 5, 'S.G/R: ' . $Cadena, 0, 0, 'R');

      $pdf->Ln();

      $pdf->Output();
      }
     */

    public function actionExportar()
    {


        $data = Consultas::getImprimirExcel();


        $filename = "TotalGuias.xlsx";

        $spreadsheet = new Spreadsheet();

        $styleArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ]
        ];

        $styleBold = [
            'font' => [
                'bold' => true,
                //'size' => 13
            ],
        ];

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells("C2:L2");
        $sheet->setCellValue('C2', 'TOTAL GUIAS');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:Z6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:Z6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        //$sheet->setCellValue('B4', 'REMITENTE:');

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'CLIENTE FINAL');
        $sheet->setCellValue('C6', 'FECHA RECOJO');
        $sheet->setCellValue('D6', 'CLIENTE DESTINO');
        $sheet->setCellValue('E6', 'GUIA CLIENTE');
        $sheet->setCellValue('F6', 'FACTURA');
        $sheet->setCellValue('G6', 'GUIA PEGASO');
        $sheet->setCellValue('H6', 'PROVINCIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'PESO VOLUMEN');
        $sheet->setCellValue('L6', 'DESCRIPCION');
        $sheet->setCellValue('M6', 'VIA');
        $sheet->setCellValue('N6', 'DATALOGER');
        $sheet->setCellValue('O6', 'EMPRESA TRANSPORTE');
        $sheet->setCellValue('P6', 'FACTURA');
        $sheet->setCellValue('Q6', 'GUIA TRANSPORTISTA');
        $sheet->setCellValue('R6', 'STATUS DE ENTREGA');
        $sheet->setCellValue('S6', 'RECIBIDO');
        $sheet->setCellValue('T6', 'FECHA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('U6', 'HORA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('V6', 'FECHA DE ENTREGA REAL DE LA DOCUMENTACION AL CLIENTE');
        $sheet->setCellValue('W6', 'OBSERVACION EN EL CASO NO CUMPLA CON LOS REQUISITOS');
        $sheet->setCellValue('X6', 'QUIEN RELIZO LA ENTREGA');
        $sheet->setCellValue('Y6', 'USUARIO REG');
        $sheet->setCellValue('Z6', 'ESTADO GUIA PEGASO');
        $sheet->setCellValue('AA6', 'FECHA REG');


        /*       $sheet->setCellValue('A6', 'ITEM');
          $sheet->setCellValue('B6', 'DESTINO');
          $sheet->setCellValue('C6', 'CLIENTE');
          $sheet->setCellValue('D6', 'GUIA DE PEGASO');
          $sheet->setCellValue('E6', 'GUIA DEL CLIENTE');
          $sheet->setCellValue('F6', 'CANTIDAD');
          $sheet->setCellValue('G6', 'PESO');
          $sheet->setCellValue('H6', 'DESCRIPCION');
          $sheet->setCellValue('I6', 'VIA');
          $sheet->setCellValue('J6', 'EMPRESA/AGENCIA');
          $sheet->setCellValue('K6', 'FACTURA');
          $sheet->setCellValue('L6', 'GUIA');
          $sheet->setCellValue('M6', 'TOTAL');
         */

        $i = 7;
        foreach ($data as $k => $v) {


            $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['remitente']);
            $sheet->setCellValue('C' . $i, $v['fecha_traslado']);
            $sheet->setCellValue('D' . $i, $v['destinatario']);
            $sheet->setCellValue('E' . $i, $v['guia_cliente']);
            $sheet->setCellValue('F' . $i, $v['ft']);
            $sheet->setCellValue('G' . $i, $v['numero_guia']);
            $sheet->setCellValue('H' . $i, $v['destino']);
            $sheet->setCellValue('I' . $i, $v['cantidad']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['volumen']);
            $sheet->setCellValue('L' . $i, $v['unidad_medida']);
            $sheet->setCellValue('M' . $i, $v['via']);
            $sheet->setCellValue('N' . $i, $v['datalogger']);
            $sheet->setCellValue('O' . $i, $v['transportista']);
            $sheet->setCellValue('P' . $i, $v['factura_transportista']);
            $sheet->setCellValue('Q' . $i, $v['guia_remision_transportista']);
            $sheet->setCellValue('R' . $i, $v['estado_mercaderia']);
            $sheet->setCellValue('S' . $i, $v['recibido_por']);
            $sheet->setCellValue('T' . $i, $v['fecha_hora_entrega']);
            $sheet->setCellValue('U' . $i, $v['hora_entrega']);
            $sheet->setCellValue('V' . $i, $v['fecha_cargo']);
            $sheet->setCellValue('W' . $i, $v['observacion']);
            $sheet->setCellValue('X' . $i, $v['realizo_entrega']);
            $sheet->setCellValue('Y' . $i, $v['usuario']);
            $sheet->setCellValue('z' . $i, $v['estadoGuiaPegaso']);
            $sheet->setCellValue('AA' . $i, $v['fecha_reg']);


            $i++;
        }

        $sheet->getStyle('A6' . ':Z' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $drawing->setWorksheet($sheet);

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/vnd.ms-excel');
        $headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');

        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }

    public function actionImprimirv($id)
    {
        $data = Consultas::getImprimirRotulado($id, Yii::$app->user->getId());

        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'), 10, 1, 80);
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->Ln(40);
        $textypos = 59;

        $pdf->Cell(25);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 17);
        $pdf->Cell(5, $textypos, "REMITENTE");
        $pdf->setY(2);
        $pdf->setX(85);
        $pdf->SetFont('ARIAL', 'B', 17);
        $pdf->Cell(5, $textypos, "GRT");
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['remitente']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 20);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['nd_remitente']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->Cell(5, $textypos + 10, '__________________________________________');

        $pdf->Cell(25);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 17);
        $pdf->Cell(5, $textypos = $textypos + 29, "CONSIGNADO");
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['destinatario']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 20);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['nd_destino']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->Cell(5, $textypos + 15, '__________________________________________');
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, $textypos = $textypos + 28, "NR BULTO");
        $pdf->setY(2);
        $pdf->setX(35);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, $textypos, "SERVICIO");
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 12);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['cantidad']);
        $pdf->setY(2);
        $pdf->setX(35);
        $pdf->SetFont('ARIAL', 'B', 12);
        $pdf->Cell(5, $textypos, $data['nombre_producto']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, $textypos + 25, "COMPROBANTE");
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->Cell(5, $textypos = $textypos + 39, "GUIA DE REMISION " . $data['numero_guia']);
        $pdf->setY(162);
        $pdf->setX(2);


        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, "Usuario : " . $data['usuario']);
        $pdf->setY(169);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, "Fecha : " . date("d/m/Y"));


        $pdf->setY(120);
        $pdf->setX(20);
        //  $pdf->SetFont('ARIAL', '', 15);
        $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . $data['numero_guia'] . '&.png');


        //  $pdf->Cell(5, $textypos + 10, '________
        //  
        //  __________________________________');

        $pdf->Output();
    }

    public function actionExportarMes()
    {

        $data = Consultas::getExExcel($_POST["fecha"]);


        $filename = "TotalGuias.xlsx";

        $spreadsheet = new Spreadsheet();


        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ]
        ];

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells("C2:L2");
        $sheet->setCellValue('C2', 'TOTAL GUIAS');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:Z6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:Z6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        //$sheet->setCellValue('B4', 'REMITENTE:');

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'CLIENTE FINAL');
        $sheet->setCellValue('C6', 'FECHA RECOJO');
        $sheet->setCellValue('D6', 'CLIENTE DESTINO');
        $sheet->setCellValue('E6', 'GUIA CLIENTE');
        $sheet->setCellValue('F6', 'FACTURA');
        $sheet->setCellValue('G6', 'GUIA PEGASO');
        $sheet->setCellValue('H6', 'PROVINCIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'PESO VOLUMEN');
        $sheet->setCellValue('L6', 'DESCRIPCION');
        $sheet->setCellValue('M6', 'VIA');
        $sheet->setCellValue('N6', 'DATALOGER');
        $sheet->setCellValue('O6', 'EMPRESA TRANSPORTE');
        $sheet->setCellValue('P6', 'FACTURA');
        $sheet->setCellValue('Q6', 'GUIA TRANSPORTISTA');
        $sheet->setCellValue('R6', 'STATUS DE ENTREGA');
        $sheet->setCellValue('S6', 'RECIBIDO');
        $sheet->setCellValue('T6', 'FECHA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('U6', 'HORA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('V6', 'FECHA DE ENTREGA REAL DE LA DOCUMENTACION AL CLIENTE');
        $sheet->setCellValue('W6', 'OBSERVACION EN EL CASO NO CUMPLA CON LOS REQUISITOS');
        $sheet->setCellValue('X6', 'QUIEN RELIZO LA ENTREGA');
        $sheet->setCellValue('Y6', 'USUARIO REG');
        $sheet->setCellValue('Z6', 'ESTADO GUIA PEGASO');
        $sheet->setCellValue('AA6', 'FECHA REG');

        $i = 7;
        foreach ($data as $k => $v) {


            $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['remitente']);
            $sheet->setCellValue('C' . $i, $v['fecha_traslado']);
            $sheet->setCellValue('D' . $i, $v['destinatario']);
            $sheet->setCellValue('E' . $i, $v['guia_cliente']);
            $sheet->setCellValue('F' . $i, $v['ft']);
            $sheet->setCellValue('G' . $i, $v['numero_guia']);
            $sheet->setCellValue('H' . $i, $v['destino']);
            $sheet->setCellValue('I' . $i, $v['cantidad']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['volumen']);
            $sheet->setCellValue('L' . $i, $v['unidad_medida']);
            $sheet->setCellValue('M' . $i, $v['via']);
            $sheet->setCellValue('N' . $i, $v['datalogger']);
            $sheet->setCellValue('O' . $i, $v['transportista']);
            $sheet->setCellValue('P' . $i, $v['factura_transportista']);
            $sheet->setCellValue('Q' . $i, $v['guia_remision_transportista']);
            $sheet->setCellValue('R' . $i, $v['estado_mercaderia']);
            $sheet->setCellValue('S' . $i, $v['recibido_por']);
            $sheet->setCellValue('T' . $i, $v['fecha_hora_entrega']);
            $sheet->setCellValue('U' . $i, $v['hora_entrega']);
            $sheet->setCellValue('V' . $i, $v['fecha_cargo']);
            $sheet->setCellValue('W' . $i, $v['observacion']);
            $sheet->setCellValue('X' . $i, $v['realizo_entrega']);
            $sheet->setCellValue('Y' . $i, $v['usuario']);
            $sheet->setCellValue('z' . $i, $v['estadoGuiaPegaso']);
            $sheet->setCellValue('AA' . $i, $v['fecha_reg']);


            $i++;
        }

        $sheet->getStyle('A6' . ':Z' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $drawing->setWorksheet($sheet);

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/vnd.ms-excel');
        $headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        //print_r($sheet);die();
        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }

    public function actionSolicitarPermiso()
    {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            $usuario = User::findOne(Yii::$app->user->getId());
            $nombresPersona = Personas::find()->where(["fecha_del" => null, "id_persona" => $usuario->id_persona])->one();
            $nombres = $nombresPersona->nombres . " " . $nombresPersona->apellido_paterno . " " . $nombresPersona->apellido_materno;

            $guiaRemision = GuiaRemision::find()->where(["fecha_del" => null, "id_guia_remision" => $post["id_guia"]])->one();
            try {

                setlocale(LC_TIME, "spanish");

                $final = false;

                $mensaje = "  <br><br>El usuario " . $nombres . " <br><br>Solicita el cambio de estado de la guia " . $guiaRemision->numero_guia . "<br> <br><br>
                         <a href='http://147.182.244.87/pegaso/web/externo/api/status/" . $post["id_guia"] . "?code=" . Yii::$app->security->generatePasswordHash($post["id_guia"]) . "'>
            <button type='button' class='btn btn-primary mr-2'>Ir al Sistema</button></a>          
            
               <br>
             Atentamente, <br>  <br> <br>  " .
                    '<html><body> <img src="http://147.182.244.87/pegaso/web/assets/3091f183/media/logos/pegasologo.png" width="300" height="100" class="max-h-30px" alt="" /> </body>  </html> ';

                try {
                    $correo = 'armandojulio82@gmail.com;armando_J07@hotmail.com;administracion@pegasoserviceexpress.com';
                    $correo_clientes = explode(";", $correo);
                    $mail = Yii::$app->mailer->compose()
                        ->setFrom('seguimiento@pegasoserviceexpress.com')
                        ->setSubject('CAMBIO DE ESTADO DE GUIA ')
                        ->setHtmlBody($mensaje);


                    foreach ($correo_clientes as $receiver) {
                        $mail->setTo($receiver)
                            ->send();
                    };


                    $final = true;
                } catch (Exception $ex) {
                    Utils::show($ex, true);
                    $final = false;
                }

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = $final;

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $post["id_guia"];
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }


}
