<?php

namespace app\modules\guiaventas\controllers;

use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\ClientesVentas;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\guiaventas\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaVenta;
use app\models\FormaPago;
use app\models\TipoComprobante;
use app\models\TipoDocumentos;
use app\models\TipoEntrega;
use app\models\DetalleGuiaVenta;
use app\models\GuiaVentaDestino;
use app\models\Ubigeos;
use yii\filters\AccessControl;
use yii\helpers\Url;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Default controller for the `guiaventas` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'get-modal-facturacion',
                            'reg-entidad',
                            'crear-entidad',
                            'crear',
                            'create',
                            'buscar-e',
                            'editar',
                            'update',
                            'delete',
                            'lista',
                            'validar',
                            'imprimir',
                            'imprimirv',
                            'factura',
                            'imprimir-factura',
                            'desarrollo',
                            'buscar-documento',
                            'buscar-guia',
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetModalFacturacion($id)
    {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaFacturacion(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $plantilla = Yii::$app->controller->renderPartial("facturacion", [
            "facturac" => $result,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionRegEntidad($id)
    {

        $tipo_entidad = \app\models\TipoEntidad::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();

        $plantilla = Yii::$app->controller->renderPartial("crearE", [
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos,
            "numerodoc" => $id,
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
                $entidades = new ClientesVentas();
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

        $formapago = FormaPago::find()->where(["fecha_del" => null])->all();
        $tipodocumento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $tipocomprobante = TipoComprobante::find()->where(["fecha_del" => null])->all();
        $tipoentrega = TipoEntrega::find()->where(["fecha_del" => null])->all();
        $via = Via::find()->where(["fecha_del" => null])->all();
        $rem_des_client = ClientesVentas::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
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
            "tipocomprobante" => $tipocomprobante,
            "tipoentrega" => $tipoentrega,
            "tipodocumento" => $tipodocumento,
            "formapago" => $formapago,
            "ubigeos" => $ubigeos
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {

                $guiaVenta = new GuiaVenta();
                $guiaVenta->serie = $post["serie"];
                $guiaVenta->numero_guia = $post["numero"];
                $guiaVenta->fecha = $post["fecha"];
                $guiaVenta->id_forma_pago = $post["id_forma_pago"];
                $guiaVenta->id_tipo_comprobante = $post["id_tipo_comprobante"];
                $guiaVenta->id_conductor = $post["conductor"];
                $guiaVenta->id_vehiculo = $post["vehiculo"];
                $guiaVenta->id_tipo_documento_remitente = $post["tipo_documento"];
                $guiaVenta->id_entidad_ = $post["id_entidad_"];
                $guiaVenta->guia_cliente = $post["guia_cliente"];
                $guiaVenta->flg_factura = 0;
                $guiaVenta->id_estado = Utils::PENDIENTE;
                $guiaVenta->id_usuario_reg = Yii::$app->user->getId();
                $guiaVenta->fecha_reg = Utils::getFechaActual();
                $guiaVenta->ipmaq_reg = Utils::obtenerIP();
                $guiaVenta->id_agente = $post["agenteasg"];


                if (!$guiaVenta->save()) {
                    Utils::show($guiaVenta->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $detalleGuia = new DetalleGuiaVenta();
                $detalleGuia->id_guia_venta = $guiaVenta->id_guia_venta;
                $detalleGuia->id_producto = $post["producto"];
                $detalleGuia->descripcion_producto = $post["descripcion_producto"];
                $detalleGuia->id_forma_envio = $post["forma_envio"];
                $detalleGuia->cantidad = $post["cantidad"];
                $detalleGuia->peso = $post["peso"];
                $detalleGuia->volumen = $post["volumen"];
                $detalleGuia->monto_envio = $post["monto_envio"];

                $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
                $detalleGuia->fecha_reg = Utils::getFechaActual();
                $detalleGuia->ipmaq_reg = Utils::obtenerIP();
                if (!$detalleGuia->save()) {
                    Utils::show($detalleGuia->getErrors(), true);
                    throw new HttpException("No se puede guardar datos detalle guia");
                }

                $guiaVentaDestino = new GuiaVentaDestino();
                $guiaVentaDestino->id_guia_remision = $guiaVenta->id_guia_venta;
                $guiaVentaDestino->id_tipo_documento = $post["tipo_dni_usuario_des"];
                $guiaVentaDestino->numero_documento = $post["numero_documento"];
                $guiaVentaDestino->nombres_destinatario = $post["nombre_destinatario"];
                $guiaVentaDestino->otro_consignado = $post["otroconsigando_gv"];
                $guiaVentaDestino->celular_destinatario = $post["celular_destinatario"];
                $guiaVentaDestino->id_tipo_entrega = $post["tipo_entrega"];
                $guiaVentaDestino->id_agente = $post["agente"];
                $guiaVentaDestino->id_ubigeo = $post["ubigeos_gv"];
                $guiaVentaDestino->direccion_destinatario = $post["direccion_gv"];
                $guiaVentaDestino->observacion = $post["observacion_gv"];


                $guiaVentaDestino->id_usuario_reg = Yii::$app->user->getId();
                $guiaVentaDestino->fecha_reg = Utils::getFechaActual();
                $guiaVentaDestino->ipmaq_reg = Utils::obtenerIP();

                if (!$guiaVentaDestino->save()) {
                    Utils::show($guiaVentaDestino->getErrors(), true);
                    throw new HttpException("No se puede guardar datos detalle guia");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($guiaVenta->id_guia_venta);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionBuscarGuia() {
        $numerog = $_POST["numero"];
        $serie = $_POST["serie"];

        $guiaventa =Consultas::getCantidadGv($numerog,$serie );
       // $guiaventa=GuiaVenta::find()->where(["fecha_del" => null, "serie" => $serie,  "numero_guia" => $numerog])->all();;

        /*  $result = null;
        try {

            $command = Yii::$app->db->createCommand('call consultaNumeroGuia(:numero_guia,:serie_guia)');
            $command->bindValue(':numero_guia', $numerog);
            $command->bindValue(':serie_guia', $serie);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        } */

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guiaventa;
    }

    public function actionBuscarE()
    {

        $numero_documentob = $_POST["numero_documentob"];
        $tipo_documentob = $_POST["tipo_documentob"];
        $entidad = ClientesVentas::find()->where(["fecha_del" => null, "numero_documento" => $numero_documentob,  "id_tipo_documento" => $tipo_documentob])->all();

        $data = [];
        $id_entid = "";
        foreach ($entidad as $d) {
            $data = $d;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarDocumento()
    {
        $numero_documentob = $_POST["numero_documento_entidad"];
        $tipoDocumento = $_POST["tipo_documento_entidad"];
        //     print_r($tipoDocumento)
        $tipodc = null;
        if ($tipoDocumento == 1) {

            $tipodc = 'dni';
        } else if ($tipoDocumento == 2) {
            $tipodc = 'ruc';
        }

        $docservico = Utils::getConsultaDocumento($tipodc, $numero_documentob);

        //   echo $docservico;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $docservico;


    }

    public function actionEditar($id)
    {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaGV(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $formapago = FormaPago::find()->where(["fecha_del" => null])->all();
        $tipodocumento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $tipocomprobante = TipoComprobante::find()->where(["fecha_del" => null])->all();
        $tipoentrega = TipoEntrega::find()->where(["fecha_del" => null])->all();
        $via = Via::find()->where(["fecha_del" => null])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        $guia = GuiaVenta::findOne($id);

        return $this->render('editar', [
            "guiaventas" => $result,
            "via" => $via,
            "agente" => $agente,
            "conductor" => $conductor,
            "vehiculo" => $vehiculo,
            "producto" => $producto,
            "tipoCarga" => $tipoCarga,
            "tipocomprobante" => $tipocomprobante,
            "tipoentrega" => $tipoentrega,
            "tipodocumento" => $tipodocumento,
            "formapago" => $formapago,
            "ubigeos" => $ubigeos,
            "guia" => $guia
        ]);
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaVenta = GuiaVenta::findOne($post["id_guia_venta"]);
                $guiaVenta->serie = $post["serie"];
                $guiaVenta->numero_guia = $post["numero"];
                $guiaVenta->fecha = $post["fecha"];
                $guiaVenta->id_forma_pago = $post["id_forma_pago"];
                $guiaVenta->id_tipo_comprobante = $post["id_tipo_comprobante"];
                $guiaVenta->id_conductor = $post["conductor"];
                $guiaVenta->id_vehiculo = $post["vehiculo"];
                $guiaVenta->id_tipo_documento_remitente = $post["tipo_documento"];
                $guiaVenta->id_entidad_ = $post["id_entidad_"];
                $guiaVenta->guia_cliente = $post["guia_cliente"];
                $guiaVenta->id_estado = Utils::PENDIENTE;
                $guiaVenta->id_usuario_act = Yii::$app->user->getId();
                $guiaVenta->fecha_act = Utils::getFechaActual();
                $guiaVenta->ipmaq_act = Utils::obtenerIP();
                $guiaVenta->id_agente = $post["agenteasg"];

                if (!$guiaVenta->save()) {
                    Utils::show($guiaVenta->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $detalleGuia = DetalleGuiaVenta::findOne($post['id_detalle_guia_venta']);
                $detalleGuia->id_detalle_guia_venta = $post["id_detalle_guia_venta"];
                $detalleGuia->id_guia_venta = $guiaVenta->id_guia_venta;
                $detalleGuia->id_producto = $post["producto"];
                $detalleGuia->descripcion_producto = $post["descripcion_producto"];
                $detalleGuia->id_forma_envio = $post["forma_envio"];
                $detalleGuia->cantidad = $post["cantidad"];
                $detalleGuia->peso = $post["peso"];
                $detalleGuia->volumen = $post["volumen"];
                $detalleGuia->monto_envio = $post["monto_envio"];
                $detalleGuia->id_usuario_act = Yii::$app->user->getId();
                $detalleGuia->fecha_act = Utils::getFechaActual();
                $detalleGuia->ipmaq_act = Utils::obtenerIP();

                if (!$detalleGuia->save()) {
                    Utils::show($detalleGuia->getErrors(), true);
                    throw new HttpException("No se puede guardar datos detalle guia");
                }
                $guiaVentaDestino = GuiaVentaDestino::findOne($post['id_guia_venta_destino']);
                $guiaVentaDestino->id_guia_venta_destino = $post["id_guia_venta_destino"];
                $guiaVentaDestino->id_guia_remision = $guiaVenta->id_guia_venta;
                $guiaVentaDestino->id_tipo_documento = $post["tipo_dni_usuario_des"];
                $guiaVentaDestino->numero_documento = $post["numero_documento"];
                $guiaVentaDestino->nombres_destinatario = $post["nombre_destinatario"];
                $guiaVentaDestino->otro_consignado = $post["otroconsigando_gv"];
                $guiaVentaDestino->celular_destinatario = $post["celular_destinatario"];
                $guiaVentaDestino->id_tipo_entrega = $post["id_tipo_entrega"];
                $guiaVentaDestino->id_agente = $post["agente"];
                $guiaVentaDestino->id_ubigeo = $post["ubigeos_gv"];
                $guiaVentaDestino->direccion_destinatario = $post["direccion_gv"];
                $guiaVentaDestino->observacion = $post["observacion_gv"];
                $guiaVentaDestino->id_usuario_reg = Yii::$app->user->getId();
                $guiaVentaDestino->fecha_reg = Utils::getFechaActual();
                $guiaVentaDestino->ipmaq_reg = Utils::obtenerIP();

                if (!$guiaVentaDestino->save()) {
                    Utils::show($guiaVentaDestino->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision cliente");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($guiaVenta->id_guia_venta);
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
                $guiaVenta = GuiaVenta::findOne($post["id_guia_venta"]);
                $guiaVenta->id_estado = Utils::ANULADO;
                $guiaVenta->id_usuario_act = Yii::$app->user->getId();
                $guiaVenta->fecha_act = Utils::getFechaActual();
                $guiaVenta->ipmaq_act = Utils::obtenerIP();

                if (!$guiaVenta->save()) {
                    Utils::show($guiaVenta->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaVenta->id_guia_venta;
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
        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoGuiaVenta(:row,:length,:buscar,@total)');
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
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="guiaventas/default/imprimirv/' . $row["id_guia_venta"] . '"><i class="icon-2x flaticon-doc"></i></a> '
                . '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="guiaventas/default/imprimir/' . $row["id_guia_venta"] . '"><i class="flaticon2-print"></i></a>
                        <a class="btn btn-icon btn-light-success btn-sm mr-2" href="guiaventas/default/editar/' . $row["id_guia_venta"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["flg_factura"] == 0) {
                $botones .= ' <a class="btn btn-icon btn-light-info btn-sm mr-2"  onclick="funcionFacturacion(' . $row["id_guia_venta"] . ')"><i class="flaticon-coins"></i></a>';
            }
            if ($row["nombre_estado"] == "PENDIENTE") {
                $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminarGuia(' . $row["id_guia_venta"] . ')"><i class="flaticon-delete"></i></button>';
            }

            $data[] = [
                "numero_guia" => $row['numero_guia'],
                "forma_pago" => $row['forma_pago'],
                "fecha" => $row['fecha'],
                "nombre_estado" => $row['nombre_estado'],
                "tipo_entrega" => $row['tipo_entrega'],
                "destino" => $row['destino'],
                "accion" => $botones
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

    public function actionValidar()
    {
        $numero_documentob = $_POST["numero"];
        $entidad = GuiaVenta::find()->where(["fecha_del" => null, "numero_guia" => $numero_documentob])->all();

        $data = [];
        $id_entid = "";
        foreach ($entidad as $d) {
            $data = $d;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionImprimir($id)
    {
 
        $data = Consultas::getImprimirGuiaV($id, Yii::$app->user->getId());

        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 9);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->Ln(39.5);

        $pdf->Cell(22);
        $pdf->Cell(39, 5, $data["fecha"], 0, 0, 'L');
        $pdf->Cell(20);
        $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');

        $pdf->Ln(10);

        $pdf->Cell(20);
        $pdf->Cell(75, 5, utf8_decode($data["remitente"]), 0, 0, 'L');
        $pdf->Cell(10);
        $pdf->Cell(70, 5, utf8_decode($data["destinatario"]), 0, 0, 'R');

        $pdf->Ln();

        $pdf->Cell(15);
        $pdf->Cell(80, 6, $data["doc_remitente"], 0, 0, 'L');
        $pdf->Cell(28);
        $pdf->Cell(85, 6, $data["doc_destinatario"], 0, 0, 'L');

        $pdf->Ln(10);

        $pdf->Cell(100, 7, utf8_decode($data["direccion_remitente"]), 0, 0, 'L');
        //$pdf->Cell(6);
        //$pdf->Cell(90, 7, utf8_decode($data["direccion_destinatario"]), 0, 0, 'L');
        $pdf->setY(75);
        $pdf->setX(130);
        $pdf->MultiCell(75, 3, utf8_decode($data["direccion_destinatario"]), '', 'L', 0);

        $pdf->Ln(10);

        $pdf->Cell(80, 11, $data["conductor"], 0, 0, 'R');
        $pdf->Cell(20);
        $pdf->Cell(80, 11, $data["licencia"], 0, 0, 'R');

        $pdf->Ln(7);

        $pdf->Cell(80, 7, $data["marca"], 0, 0, 'R');
        $pdf->Cell(20);
        $pdf->Cell(80, 6, $data["placa"], 0, 0, 'R');
        $pdf->Ln(17);
        $guiaDetalle = Consultas::getDetalleGuia($id);

        foreach ($guiaDetalle as $gd) {
            $pdf->Cell(11, 5, $gd["cantidad"], 0, 0, 'C');
            $pdf->Cell(20, 5, $gd["unidad_medida"], 0, 0, 'C');
            $pdf->Cell(100, 5, $gd["descripcion_producto"], 0, 0, 'C');

            $pdf->Cell(200, 5, 'PESO :' . $gd["peso"] . 'KG ', 0, 0, 'L');

            $pdf->Ln();
        }

        $Cadena = "";
        $guiaCliente = Consultas::getGuiaCliente($id);
        foreach ($guiaCliente as $gc) {
            $Cadena .= $gc["gr"] . ', ';
        }

        //$pdf->Ln(0);

        $pdf->cell(100, 5, $data["guia_cliente"], 0, 0, 'R');
        $pdf->cell(50, 15, $data["observacion"], 0, 0, 'R');

        //$pdf->Ln();
        $valor = 1;

        if ($data["otro_consignado"] != '') {
            $valor = 10;
        } else {
            $valor = 1;
        }
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Sety(126);
        $pdf->SetX(50);
        $pdf->cell(50, 0, "Telefono: " . $data["celular_destinatario"], 0, 0, 'R');
        $pdf->SetFont('ARIAL', 'B', $valor);
        $pdf->Sety(134);
        $pdf->SetX(50);
        $pdf->cell(70, 0, "Otro Consignado: " . $data["otro_consignado"], 0, 0, 'R');

        $pdf->Ln();
        $pdf->Output();
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
        $textypos = 45;

        $pdf->Cell(25);
        $pdf->setY(10);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 17);
        $pdf->Cell(5, $textypos, "REMITENTE");
        $pdf->setY(11);
        $pdf->setX(85);
        $pdf->SetFont('ARIAL', 'B', 17);
        $pdf->Cell(5, $textypos, "GRT");
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Cell(5, $textypos = $textypos + 26, $data['remitente']);
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 20);
        $pdf->Cell(5, $textypos = $textypos + 15, $data['direccion_remitente']);
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
        $pdf->Cell(5, $textypos = $textypos + 15, $data['lugardestino']);
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
        $pdf->setY(164);
        $pdf->setX(2);


        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, "Usuario : ");
        $pdf->setY(170);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, "Fecha : ");
        $pdf->setY(164);
        $pdf->setX(35);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, $data['usuario']);

        $pdf->setY(170);
        $pdf->setX(35);
        $pdf->SetFont('ARIAL', '', 15);
        $pdf->Cell(5, 1, date("d/m/Y"));
        $pdf->setY(120);
        $pdf->setX(20);
        //  $pdf->SetFont('ARIAL', '', 15);
        $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . $data['numero_guia'] . '&.png');


        //  $pdf->Cell(5, $textypos + 10, '________
        //  
        //  __________________________________');

        $pdf->Output();
    }

    public function actionFactura()
    {

        $id_guia_venta = $_POST["id_guia_venta"];

        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaFacturacion(:id_guia)');
            $command->bindValue(':id_guia', $id_guia_venta);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $tipopago = '';
        $fechacuota = '';
        if ($result["forma_pago"] == 'credito') {
            $tipopago = 'PC';
            $fechacuota = date("Y-m-d", strtotime(Utils::getFechaaActual() . "+ 1 days"));
        } else {
            $tipopago = 'CP';
            $fechacuota = '';
        }
        $correlativo = '';
        $serie = '';
        if ($result["tipo_comprobante"] === 'Factura') {
            $serie = '004';
            $correlativo = Utils::getGenerarNumeroFB('guia_ventas', '004');
        } else if ($result["tipo_comprobante"] === 'Boleta') {
            $serie = '005';
            $correlativo = Utils::getGenerarNumeroFB('guia_ventas', '005');
        }


        $tarifagenerada = 0;
        $transaction = Yii::$app->db->beginTransaction();
        if ($result != null) {

            $cn = new \app\models\VentasFactura();
            $cn->id_guia_ventas = $result["id_guia_venta"];
            $cn->serie = $serie;
            $cn->correlativo = $correlativo;
            $cn->id_tipo_comprobante = $result["id_tipo_comprobante"];
            $cn->tipo_comprobante = $result["tipo_comprobante"];
            $cn->forma_pago = $result["forma_pago"];
            $cn->tipo_forma_pago = $tipopago;
            $cn->total = $result["monto_envio"];
            $cn->igv = $result['igv'];
            $cn->subtotal = $result['precio_unitario'];
            $cn->cliente = $result['razon_social'];
            $cn->numero_documento = $result['numero_documento'];
            $cn->cantidad = $result['cantidad_pr'];
            $cn->producto = $result["nombre_producto"] . " DE " . $result["descripcion_producto"];
            $cn->fechacuota = $fechacuota;
            $cn->fecha = Utils::getFechaaActual();
            $cn->id_usuario_reg = Yii::$app->user->getId();
            $cn->fecha_reg = Utils::getFechaActual();
            $cn->ipmaq_reg = Utils::obtenerIP();
            if (!$cn->save()) {
                Utils::show($cn->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }

            $guiaVenta = \app\models\GuiaVenta::findOne($id_guia_venta);
            $guiaVenta->flg_factura = 1;
            $guiaVenta->id_usuario_act = Yii::$app->user->getId();
            $guiaVenta->fecha_act = Utils::getFechaActual();
            $guiaVenta->ipmaq_act = Utils::obtenerIP();

            if (!$guiaVenta->save()) {
                show($guiaVenta->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }
            if ($cn->id_ventas_factura > 0) {

                $curl = curl_init();

// $result["nombre_producto"]." DE ".$result["descripcion_producto"]
                if ($result["tipo_comprobante"] === 'Factura') {

                    curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://147.182.244.87:8080/facturacionempresas/invoice/send',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>
                                '{
  "ruc": "20524917891",
  "razon": "Pegaso Service Express S.A.C.",
  "direccion": "Cal. Pablo de Olavide Nro. 365",
  "userNameSunat": "20524917891ADMCARME",
  "passwordSunat": "ADm123",
  "descripcionItem":"' . $result["nombre_producto"] . " DE " . $result["descripcion_producto"] . '",
  "cantidadItems": "' . $result["cantidad_pr"] . '",
  "tipoDoc": "01",
  "desTipoDoc": "factura",
  "serie": "' . $serie . '",
  "numero": "' . $correlativo . '",
  "fechaEmision": "' . Utils::getFechaaActual() . '",
  "horaEmision": "' . Utils::getHoraActual() . '",
  "codServicio": "E",
  "desServicio": "Encomienda",
  "moneda": "PEN",
  "codTipoDocCliente": "6",
  "desCodTipoDocCliente": "Ruc",
  "nombreRazonCliente":  "' . $result['razon_social'] . '",
  "dniRucCliente":  "' . $result['numero_documento'] . '",
  "tipoPago": "' . $tipopago . '",
  "desTipoPago": "' . $result['forma_pago'] . '",
  "total": "' . $result['monto_envio'] . '",
  "totalSinIgv": "' . $result['precio_unitario'] . '",
  "igv": "' . $result['igv'] . '",
  "fechaPagoCuotaUno": "' . $fechacuota . '",
  "montoLetras": "' . strtoupper(Utils::convertirLetras($result["monto_envio"])) . '"}',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        )
                    );
                } else if ($result["tipo_comprobante"] === 'Boleta') {
                    curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://147.182.244.87:8080/facturacionempresas/invoice/send',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
  "ruc": "20524917891",
  "razon": "Pegaso Service Express S.A.C.",
  "direccion": "Cal. Pablo de Olavide Nro. 365",
  "userNameSunat": "20524917891ADMCARME",
  "passwordSunat": "ADm123",
  "descripcionItem":"' . $result["nombre_producto"] . " DE " . $result["descripcion_producto"] . '",
  "cantidadItems": "' . $result["cantidad_pr"] . '",
  "tipoDoc": "03",
  "desTipoDoc": "boleta",
  "serie": "' . $serie . '",
  "numero": "' . $correlativo . '",
  "fechaEmision": "' . Utils::getFechaaActual() . '",
  "horaEmision": "' . Utils::getHoraActual() . '",
  "codServicio": "E",
  "desServicio": "Encomienda",
  "moneda": "PEN",
  "codTipoDocCliente": "1",
  "desCodTipoDocCliente": "DNI",
  "nombreRazonCliente":  "' . $result['razon_social'] . '",
  "dniRucCliente":  "' . $result['numero_documento'] . '",
  "tipoPago": "' . $tipopago . '",
  "desTipoPago": "' . $result['forma_pago'] . '",
  "total": "' . $result['monto_envio'] . '",
  "totalSinIgv": "' . $result['precio_unitario'] . '",
  "igv": "' . $result['igv'] . '",

  "fechaPagoCuotaUno": "' . $fechacuota . '",
  "montoLetras": "' . strtoupper(Utils::convertirLetras($result["monto_envio"])) . '"}',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        )
                    );
                }


                $response = curl_exec($curl);
                $response = json_decode($response);

                $cod = "";
                $mensaje = "";
                foreach ($response as $k => $r) {
                    $cod = $response->cod;
                    $mensaje = $response->mensaje;
                }

                if ($cod == '001') {
                    $estado = 1;
                    Utils::getVentasFacturaUp(
                        $cn->id_ventas_factura, $estado
                    );
                } else if ($cod == '666') {
                    $estado = 2;
                    Utils::getVentasFacturaUp(
                        $cn->id_ventas_factura, $estado
                    );
                } else if ($cod == '1071') {
                    $estado = 3;
                    Utils::getVentasFacturaUp(
                        $cn->id_ventas_factura, $estado
                    );
                }
            }


            $transaction->commit();
        } else {
            $tarifagenerada = 0;
        }


        return $cn->id_ventas_factura;
    }

    function cvf_convert_object_to_array($data)
    {

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            return array_map(__FUNCTION__, $data);
        } else {
            return $data;
        }
    }

    /*   public function actionImprimirFactura($id)
    {


        $result = Consultas::getImprimirFactura($id);

        $tipo_doc_ide = '';
        $tipodc = '';
        if ($result["tipo_comprobante"] == 'Factura') {
            $tipo_doc_ide = 'RUC: ';
            $tipodc = 'F';

        } else if ($result["tipo_comprobante"] == 'Boleta') {
            $tipo_doc_ide = 'DNI: ';
            $tipodc = 'B';

        }
        $idUser = Yii::$app->user->getId();
        $actual = date("Y-m-d H:i:s");

        $user = \app\models\Usuarios::findOne($idUser);
        $pdf = new \FPDF();

        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/manifiestoventa/assets/images/logopegaso.png'), 10, 1, 80);
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->Ln(40);
        $textypos = 45;
        $x=0;
        $pdf->Cell(25);
        $pdf->setY(10);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 16);
        $pdf->Cell(5, $textypos, "PEGASO SERVICE EXPRESS S.A.C.");

        $pdf->setY(45);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "CALLE PABLO DE OLAVIDE 365 URB.COLONIAL");
        $pdf->setY(50);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "RUC: 20524917891");
        $pdf->setY(55);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, $result["tipo_comprobante"] . " Electronica");
        $pdf->setY(60);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Documento: " . "$tipodc" . $result["documento"]);
        $pdf->setY(65);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "FECHA: " . $actual); //----------------------------------------------
        $pdf->setY(74);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");
        $pdf->setY(76);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 10);

        $pdf->MultiCell(100, 4, "CLIENTE: " . utf8_decode($result["cliente"]), '', 'J', 0);
        $pdf->setY(86);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "N° DOC: " . $result["numero_documento"]);
        $pdf->setY(90);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");

        $pdf->setY(95);
        $pdf->setX(2);
        $header = array('CANT', 'PROD', 'VALOR');
        $w = array(40, 35, 40);
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, '');
        }
        $pdf->Ln();

        $pdf->setY(105);
        $pdf->setX(1);
        $pdf->MultiCell($w[0], 4, $result["cantidad"], '', 'J', 0);
        $pdf->setY(105);
        $pdf->setX(17);
        $pdf->MultiCell(55, 4, "" . utf8_decode($result["producto"]), '', 'J', 0);
        $pdf->setY(105);
        $pdf->setX(76);
        $pdf->MultiCell($w[2], 4, "S/" . $result["total"], '', 'J', 0);
        $pdf->setY(125);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(130);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SUBTOTAL:", '', 'J', 0);
        $pdf->setY(130);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["subtotal"], '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "IGV:", '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["igv"], '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["total"], '', 'J', 0);

        $pdf->setY(145);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SON:", '', 'J', 0);
        $pdf->setY(145);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, strtoupper(Utils::convertirLetras($result["total"])), '', 'J', 0);

        $pdf->setY(150);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(155);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "Forma de pago: " . utf8_decode($result["forma_pago"]), '', 'J', 0);

        $pdf->setY(160);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);


        $pdf->setY(165);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "AGENCIA: La Victoria", '', 'J', 0);
        $pdf->setY(170);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "USUARIO: " . $user->usuario, '', 'J', 0);
        $pdf->setY(175);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "REGISTRO: " . $actual, '', 'J', 0);

        $pdf->setY(180);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(185);
        $pdf->setX(2);
        $pdf->MultiCell(95, 4, utf8_decode("A la firma de la conformidad de la boleta la empresa se "
            . "exime de responsabilidad alguna. Respecto de la perdida de encomiendas,"
            . " la empresa está facultada a pagar hasta 10 veces el valis del flete conforme "
            . "a la RD-001-2006-MTC/19 Ley de los Servicios Postales. La Empresa no se responsabiliza "
            . "pos el deterioro, perdida u otra alteracón que pueda sufrir el el contenido de encomienda "
            . "producto del mal embalaje. La empresa no se hace responsable de aquellas encomiendas cuyo"
            . " recojo excede el plazo de 1 mes de depósito. "), '', 'J', 0);


        $pdf->Output();
    } */

    public function actionImprimirFactura($id)
    {


        $result = Consultas::getImprimirFactura($id);
        $result2 = Consultas::getConsultaFacturacion($result["id_guia_ventas"]);
        $tipo_doc_ide = '';
        $tipodc = '';
        if ($result["tipo_comprobante"] == 'Factura') {
            $tipo_doc_ide = 'RUC: ';
            $tipodc = 'F';

        } else if ($result["tipo_comprobante"] == 'Boleta') {
            $tipo_doc_ide = 'DNI: ';
            $tipodc = 'B';

        }
        $idUser = Yii::$app->user->getId();
        $actual = date("Y-m-d H:i:s");

        $user = \app\models\Usuarios::findOne($idUser);
        $usuarioconsul = Consultas::getConsultaUsuario($result["id_usuario_reg"]);
        
      //  $agencia = \app\models\Usuarios::findOne($idUser);
        $pdf = new \FPDF();

        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/manifiestoventa/assets/images/logopegaso.png'), 10, 1, 80);
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->Ln(40);
        $textypos = 45;
        $x=0;
        $pdf->Cell(25);
        $pdf->setY($x=$x+9);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 16);
        $pdf->Cell(5, $textypos, "PEGASO SERVICE EXPRESS S.A.C.");

        $pdf->setY($x=$x+30);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "CALLE PABLO DE OLAVIDE 365 URB.COLONIAL");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "RUC: 20524917891");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, $result["tipo_comprobante"] . " Electronica");
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Documento: " . "$tipodc" . $result["documento"]);
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "FECHA: " . $actual); //----------------------------------------------
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");
        $pdf->setY($x=$x+2);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 10);

        $pdf->MultiCell(100, 4, "CLIENTE: " . utf8_decode($result["cliente"]), '', 'J', 0);
        $pdf->setY($x=$x+9);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, $result2["documento"].": " . $result["numero_documento"]);
        
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Origen: " .  $usuarioconsul["origen"]);
        
        $pdf->setY($x=$x+6);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Destino: " . $result["destino"]);
        $pdf->setY($x=$x+6);

        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, utf8_decode("N° Guia: " . $result["numero_guia"]));
        $pdf->setY($x=$x+5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");
        
       

        $pdf->setY(100);
        $pdf->setX(2);
        $header = array('CANT', 'DESCRIP.', 'VALOR');
        $w = array(40, 35, 40);
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, '');
        }
        $pdf->Ln();

        $pdf->setY(110);
        $pdf->setX(1);
        $pdf->MultiCell($w[0], 4, $result["cantidad"], '', 'J', 0);
        $pdf->setY(110);
        $pdf->setX(17);
        $pdf->MultiCell(55, 4, "" . utf8_decode($result["producto"]), '', 'J', 0);
        $pdf->setY(110);
        $pdf->setX(76);
        $pdf->MultiCell($w[2], 4, "S/" . $result["total"], '', 'J', 0);
        $pdf->setY(125);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(130);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SUBTOTAL:", '', 'J', 0);
        $pdf->setY(130);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["subtotal"], '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "IGV:", '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["igv"], '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["total"], '', 'J', 0);

        $pdf->setY(145);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SON:", '', 'J', 0);
        $pdf->setY(145);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, strtoupper(Utils::convertirLetras($result["total"])), '', 'J', 0);

        $pdf->setY(150);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(155);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "Forma de pago: " . utf8_decode($result["forma_pago"]), '', 'J', 0);

        $pdf->setY(160);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);


        $pdf->setY(165);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "AGENCIA: ".$usuarioconsul["nombre_agencia"], '', 'J', 0);
        $pdf->setY(170);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "USUARIO: " . $usuarioconsul["usuario"], '', 'J', 0);
        $pdf->setY(175);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "REGISTRO: " . $actual, '', 'J', 0);

        $pdf->setY(180);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(185);
        $pdf->setX(2);
        $pdf->MultiCell(95, 4, utf8_decode("A la firma de la conformidad de la boleta la empresa se "
            . "exime de responsabilidad alguna. Respecto de la perdida de encomiendas,"
            . " la empresa está facultada a pagar hasta 10 veces el valis del flete conforme "
            . "a la RD-001-2006-MTC/19 Ley de los Servicios Postales. La Empresa no se responsabiliza "
            . "pos el deterioro, perdida u otra alteracón que pueda sufrir el el contenido de encomienda "
            . "producto del mal embalaje. La empresa no se hace responsable de aquellas encomiendas cuyo"
            . " recojo excede el plazo de 1 mes de depósito. "), '', 'J', 0);


        $pdf->Output();
    }


    public function actionDesarrollo()
    {

        // $id = $_POST['id_remitente'];
        $fecha = $_POST['fecha'];
        $razon_social = $_POST['razon_social'];
        $serie = $_POST['serie'];


        $data = Consultas::getImprimirExcel();


        $filename = "Manifiesto.xlsx";

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
        $sheet->setCellValue('C2', 'Total Guias');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
        //   $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/manifiestoventa/assets/images/logo.jpeg'); // put your path and image here
        // put your path and image here
        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:O6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:O6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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


        //    $sheet->setCellValue('A6', 'FECHA REG');
        // $sheet->setCellValue('A6', 'REMITENTE');
        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'CLIENTE');
        $sheet->setCellValue('C6', 'DESTINO');
        $sheet->setCellValue('D6', 'GUIA DE PEGASO');
        $sheet->setCellValue('E6', 'GUIA DE CLIENTE');
        $sheet->setCellValue('F6', 'FACT/BOLETA');
        $sheet->setCellValue('G6', 'QTY BULTOS');
        $sheet->setCellValue('H6', 'PESO');
        $sheet->setCellValue('I6', 'DESCRIPCION');
        $sheet->setCellValue('J6', 'VIA');
        $sheet->setCellValue('K6', 'ENTREGA');
        $sheet->setCellValue('L6', 'ESTADO');
        $sheet->setCellValue('M6', 'ESTADO VENTA');
        $sheet->setCellValue('N6', 'CONTACTO');
        $sheet->setCellValue('O6', 'MONTO');


        $i = 7;
        foreach ($data as $k => $v) {


            $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['cliente']);
            $sheet->setCellValue('C' . $i, $v['destino']);
            $sheet->setCellValue('D' . $i, $v['numero_guia']);
            $sheet->setCellValue('E' . $i, $v['guia_cliente']);
            $sheet->setCellValue('F' . $i, $v['fact_boleta']);
            $sheet->setCellValue('G' . $i, $v['bultos']);
            $sheet->setCellValue('H' . $i, $v['peso']);
            $sheet->setCellValue('I' . $i, $v['descripcion']);
            $sheet->setCellValue('J' . $i, $v['via']);
            $sheet->setCellValue('K' . $i, $v['entrega']);
            $sheet->setCellValue('L' . $i, $v['estado']);
            $sheet->setCellValue('M' . $i, $v['estado_venta']);
            $sheet->setCellValue('N' . $i, $v['contacto']);
            $sheet->setCellValue('O' . $i, $v['monto']);

            $i++;
        }

        $sheet->getStyle('A6' . ':O' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'O') as $columnID) {
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


}
