<?php

namespace app\modules\guiaremisionauxiliar\controllers;

use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\guiaremisionauxiliar\query\Consultas;
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

class DefaultController extends Controller {
    /* public function behaviors() {
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
      ],
      'allow' => true,
      'roles' => ['@'],
      ],
      ],
      ],
      ];
      } */

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
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
                    $guia_remision_cliente->cantidad = $dgr["cantidad"];
                    $guia_remision_cliente->peso = $dgr["peso"];
                    $guia_remision_cliente->volumen = $dgr["volumen"];
                    $guia_remision_cliente->alto = $dgr["alto"];
                    $guia_remision_cliente->ancho = $dgr["ancho"];
                    $guia_remision_cliente->largo = $dgr["largo"];
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
                        $guia_remision_cliente->cantidad = $dg["cantidad"];
                        $guia_remision_cliente->peso = $dg["peso"];
                        $guia_remision_cliente->volumen = $dg["volumen"];
                        $guia_remision_cliente->alto = $dg["alto"];
                        $guia_remision_cliente->ancho = $dg["ancho"];
                        $guia_remision_cliente->largo = $dg["largo"];
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
            $command = Yii::$app->db->createCommand('call listadoGuiaRemisionAuxiliar(:row,:length,:buscar,@total, :usuario_sesion)');
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
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="guiaremisionauxiliar/default/imprimir/' . $row["id_guia_remision"] . '"><i class="flaticon2-print"></i></a>
                             <a class="btn btn-icon btn-light-success btn-sm mr-2" href="guiaremisionauxiliar/default/editar/' . $row["id_guia_remision"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["nombre_estado"] == "RECOGIDO") {
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
                "remitente" => $row['remitente'],
                "destinatario" => $row['destinatario'],
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

    public function actionImprimir($id) {

        $data = Consultas::getImprimirGuia($id);

        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 9);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->Ln(32);


        $pdf->Cell(25);
        $pdf->Cell(40, 5, $data["fecha"], 0, 0, 'L');
        $pdf->Cell(20);
        $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');
        $pdf->Cell(30);
        $pdf->Cell(0, -10, $data["numero_guia"], 0, 0, 'L');
        $pdf->Ln(8);


        $pdf->setY(53.0);
        $pdf->setX(17);
        /// $pdf->Cell(20);
        $pdf->MultiCell(75, 3, utf8_decode( $data["remitente"]), '', 'J', 0);
        // $pdf->Cell(75, 5, $data["remitente"], 0, 0, 'L');
        // $pdf->Cell(10);
        $pdf->setY(53.0);
        $pdf->setX(118);
        $pdf->MultiCell(75, 3, utf8_decode($data["destinatario"]), '', 'L', 0);
        //$pdf->Cell(70, 5, $data["destinatario"], 0, 0, 'R');
        $pdf->setY(56.0);
        $pdf->Ln();

        $pdf->Cell(15.5);
        $pdf->Cell(80, 6, utf8_decode($data["doc_remitente"]), 0, 0, 'L');
        $pdf->Cell(28);
        $pdf->Cell(85, 6,utf8_decode( $data["doc_destinatario"]), 0, 0, 'L');

        
        $pdf->Ln(9);

        // $pdf->setX(25);
        // $pdf->Cell(10);
        $pdf->setY(67);
        $pdf->setX(19.5);
        $pdf->MultiCell(75, 3, utf8_decode($data["direccion_remitente"]), '', 'R', 0);
        //   $pdf->Cell(100, 9, $data["direccion_remitente"], 0, 0, 'L');
        //   $pdf->Cell(16);
        $pdf->setY(67);
        $pdf->setX(130);
        $pdf->MultiCell(75, 3, utf8_decode($data["direccion_destinatario"]), '', 'L', 0);
        //  $pdf->Cell(95, 8, $data["direccion_destinatario"], 0, 0, 'L');

        $pdf->Ln(3.5);
        $pdf->setY(77);
        $pdf->Cell(83, 11, utf8_decode($data["conductor"]), 0, 0, 'R');
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

            $pdf->Cell(3);
            $pdf->Cell($w[0], 3, $row["cantidad_detalle"], '', 0, 'L', $fill);
            $pdf->Cell($w[1], 3, $row["unidad_medida"], '', 0, 'L', $fill);
            $pdf->Cell($w[2], 3, "GuiaCl: ".$row["guia_cliente"]." Peso: ".$row["peso"]." Volumen: ".$row["volumen"]." Descr: ".$row["descripcion"], '', 0, 'L', $fill);
            $pdf->Ln();
            //$fill = !$fill;
        }

        // Closing line
        $pdf->Cell(6);
        $pdf->Cell(array_sum($w), 0, '', '');
        $pdf->Ln();
        $pdf->SetFont('ARIAL', 'B', 10);
       $pdf->setY(110);
        $pdf->setX(150);
        $pdf->Cell(90, 2, utf8_decode("N° PEDIDO: ").$data["solicitudcl"], 0, 0, 'L');
        $pdf->Output();
    }



    /* public function actionImprimir($id) {

      $data = Consultas::getImprimirGuia($id);

      $pdf = new \FPDF();
      $pdf->AddPage('P', 'A4');
      $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
      $pdf->SetFont('ARIAL', 'B', 9);
      $pdf->SetAutoPageBreak(true, 10);

      $pdf->Ln(43);


      $pdf->Cell(25);
      $pdf->Cell(40, 5, $data["fecha"], 0, 0, 'L');
      $pdf->Cell(20);
      $pdf->Cell(30, 5, $data["fecha_traslado"], 0, 0, 'L');
      $pdf->Cell(30);
      $pdf->Cell(0, -10, $data["numero_guia"], 0, 0, 'L');
      $pdf->Ln(10);


      //  $pdf->setY(160);
      $pdf->setX(17);
      /// $pdf->Cell(20);
      $pdf->MultiCell(75, 3,$data["remitente"], '', 'J', 0);
      // $pdf->Cell(75, 5, $data["remitente"], 0, 0, 'L');
      // $pdf->Cell(10);
      $pdf->setY(61.5);
      $pdf->setX(107);
      $pdf->MultiCell(75, 3,$data["destinatario"], '', 'L', 0);
      //$pdf->Cell(70, 5, $data["destinatario"], 0, 0, 'R');
      $pdf->setY(64.5);
      $pdf->Ln();

      $pdf->Cell(15.5);
      $pdf->Cell(80, 6, $data["doc_remitente"], 0, 0, 'L');
      $pdf->Cell(28);
      $pdf->Cell(85, 6, $data["doc_destinatario"], 0, 0, 'L');

      //$pdf->setY(60);
      $pdf->Ln(9);

      // $pdf->setX(25);
      // $pdf->Cell(10);
      $pdf->setX(15);
      $pdf->MultiCell(75, 3,$data["direccion_remitente"], '', 'R', 0);
      //   $pdf->Cell(100, 9, $data["direccion_remitente"], 0, 0, 'L');
      //   $pdf->Cell(16);
      $pdf->setY(75.5);
      $pdf->setX(125);
      $pdf->MultiCell(75, 3,$data["direccion_destinatario"], '', 'L', 0);
      //  $pdf->Cell(95, 8, $data["direccion_destinatario"], 0, 0, 'L');

      $pdf->Ln(3.5);

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
      $Cadena .= $gc["gr"] . ', ';
      }

      $pdf->Cell(180, 5, 'S.G/R: ' . $Cadena, 0, 0, 'R');

      $pdf->Ln();

      $pdf->Output();
      } */

    public function actionExportar() {


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

        $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/SistemaPegaso/modules/manifiestoventa/assets/images/logo.jpeg');
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
        $sheet->setCellValue('Z6', 'FECHA REG');


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
            $sheet->setCellValue('W' . $i, $v['obsevacion']);
            $sheet->setCellValue('X' . $i, $v['realizo_entrega']);
            $sheet->setCellValue('Y' . $i, $v['usuario']);
            $sheet->setCellValue('Z' . $i, $v['fecha_reg']);


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

}
