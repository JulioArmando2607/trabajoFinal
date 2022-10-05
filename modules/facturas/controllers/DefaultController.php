<?php

namespace app\modules\facturas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
use yii\filters\AccessControl;
use app\modules\facturas\query\Consultas;
use yii\helpers\Url;

/**
 * Default controller for the `via` module
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
                            'lista',
                            'imprimir-factura',
                            'get-modal',
                            'nota-credito',
                            'get-modal-notacredito',
                            'get-modal-notacreditov',
                            'get-modal-facturacion',
                            'factura',
                            'nota-credito-f',
                            'nota-credito-fv',
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
        $cod_notas = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $series = Consultas::getSerie();
        $plantilla = Yii::$app->controller->renderPartial("notacredito", [
            "series" => $series,
            "cod_notas" => $cod_notas,
            "tipo_documento" => $tipo_documento]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionGetModalNotacredito($id) {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaFactura(:idFactura)');
            $command->bindValue(':idFactura', $id);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $nota_cred = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $cod_notas = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $series = Consultas::getSerie();
        $plantilla = Yii::$app->controller->renderPartial("notacreditoe", [
            "notacredito" => $result,
            "nota_cred" => $nota_cred,
            "series" => $series,
            "cod_notas" => $cod_notas,
            "tipo_documento" => $tipo_documento]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }


    public function actionGetModalNotacreditov($id) {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call ConsultaNotasCredito(:idNotaCredito)');
            $command->bindValue(':idNotaCredito', $id);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $nota_cred = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $cod_notas = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $series = Consultas::getSerie();
        $plantilla = Yii::$app->controller->renderPartial("notacreditoev", [
            "notacredito" => $result,
            "nota_cred" => $nota_cred,
            "series" => $series,
            "cod_notas" => $cod_notas,
            "tipo_documento" => $tipo_documento]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }


    public function actionGetModalFacturacion($id) {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaFactura(:idFactura)');
            $command->bindValue(':idFactura', $id);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $resultf = [];
        try {
            $commandf = Yii::$app->db->createCommand('call consultaFacturacion(:id_guia_venta)');
            $commandf->bindValue(':id_guia_venta', $result["id_guia_ventas"]);
            $resultf = $commandf->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }


        $nota_cred = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $cod_notas = \app\models\CodigosNotaCredito::find()->where(["fecha_del" => null])->all();
        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $series = Consultas::getSerie();
        $plantilla = Yii::$app->controller->renderPartial("facturacion", [
            "lfactura" => $result,
            "nota_cred" => $nota_cred,
            "series" => $series,
            "cod_notas" => $cod_notas,
            "tipo_documento" => $tipo_documento,
            "guiaventas" => $resultf]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoFacturas(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a  class="btn btn-icon btn-light-primary mr-2" target="_blank" href="facturas/default/imprimir-factura/' . $row["id_ventas_factura"] . '"><i class="flaticon-eye"></i></a>
                              <a  class="btn btn-icon btn-light-danger mr-2" target="_blank" onclick="NotaCredito(' . $row["id_ventas_factura"] . ')"><i class="flaticon-delete"></i></a>
                             ';

            if ($row["estado_codigo"] == '666' || $row["estado_codigo"] == null) {
                $botones .= ' <a class="btn btn-icon btn-light-info btn-sm mr-2"  onclick="funcionFacturacion(' . $row["id_ventas_factura"] . ')"><i class="flaticon-coins"></i></a>';
            }


            $data[] = [
                "serie" => $row['serie'],
                "correlativo" => $row['correlativo'],
                "guia_venta" => $row['guia_venta'],
                "fecha_reg" => $row['fecha_reg'],
                "total_monto" => $row['total_monto'],
                "cliente" => $row['cliente'],
                "numero_documento" => $row['numero_documento'],
                "tipo_comprobante" => $row['tipo_comprobante'],
                "estado" => $row['estado'],
                "accion" => $botones,
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

        Utils::jsonEncode($json_data);
    }

    public function actionImprimirFactura($id) {


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
        $usuarioconsul = Consultas::getConsultaUsuario($result["id_usuario_reg"]);
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
        $pdf->Cell(5, 0, "FECHA: " . $result['fecha_reg']); //----------------------------------------------
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
        $pdf->Cell(5, 0, "" . $tipo_doc_ide . $result["numero_documento"]);
        
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
        $pdf->setX(76);
        $pdf->MultiCell(0, 4, "S/" . $result["subtotal"], '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "IGV:", '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(76);
        $pdf->MultiCell(0, 4, "S/" . $result["igv"], '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(76);
        $pdf->MultiCell(0, 4, "S/" . $result["total"], '', 'J', 0);

        $pdf->setY(145);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "", '', 'J', 0);
        $pdf->setY(145);
        $pdf->setX(2);
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
        $pdf->MultiCell(0, 4, "AGENCIA: ". $usuarioconsul["nombre_agencia"], '', 'J', 0);
        $pdf->setY(170);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "USUARIO: " . $result['usuario'], '', 'J', 0);
        $pdf->setY(175);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "REGISTRO: " . $result['fecha_reg'], '', 'J', 0);

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

  
    public function actionFactura() {

        $id_ventas_factura = $_POST["id_ventas_factura"];


        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaFactura(:idFactura)');
            $command->bindValue(':idFactura', $id_ventas_factura);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $monto_envio = 0;
        $precio_unitario = 0;
        $igv = 0;

        if (empty($result['monto_envio'])) {
            $monto_envio = $_POST["monto_envio"];
            $precio_unitario = $_POST["precio_unitario"];
            $igv = $_POST["igv"];

            $ventasFactura = \app\models\VentasFactura::findOne($id_ventas_factura);
            $ventasFactura->total = $monto_envio;
            $ventasFactura->igv = $igv;
            $ventasFactura->subtotal = $precio_unitario;
            $ventasFactura->id_usuario_act = Yii::$app->user->getId();
            $ventasFactura->fecha_act = Utils::getFechaActual();
            $ventasFactura->ipmaq_act = Utils::obtenerIP();
            if (!$ventasFactura->save()) {
                Utils::show($ventasFactura->getErrors(), true);
                throw new Exception("No se puede guardar datos guia remision");
            }
        } else {
            $monto_envio = $result['total_m'];
            $precio_unitario = $result['subtotal'];
            $igv = $result['igv'];
        }


        $tipopago = '';
        $fechacuota = '';
        if ($result["forma_pago"] == 'credito') {
            $tipopago = 'PC';
            $fechacuota = date("Y-m-d", strtotime($result['fecha'] . "+ 1 days"));
        } else {
            $tipopago = 'CP';
            $fechacuota = '';
        }

        $tarifagenerada = 0;
        $transaction = Yii::$app->db->beginTransaction();
        if ($result != null) {


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
          "descripcionItem":"' . $result["producto"] . '",
          "cantidadItems": "' . $result["cantidad"] . '",
          "tipoDoc": "01",
          "desTipoDoc": "factura",
          "serie": "' . $result["serie"] . '",
          "numero": "' . $result["correlativo"] . '",
          "fechaEmision": "' . date("Y-m-d", strtotime($result['fecha_reg'])) . '",
          "horaEmision": "' . date("H:i:s", strtotime($result['fecha_reg'])) . '",
          "codServicio": "E",
          "desServicio": "Encomienda",
          "moneda": "PEN",
          "codTipoDocCliente": "6",
          "desCodTipoDocCliente": "Ruc",
          "nombreRazonCliente":  "' . $result['cliente'] . '",
          "dniRucCliente":  "' . $result['numero_documento'] . '",
          "tipoPago": "' . $result['tipo_forma_pago'] . '",
          "desTipoPago": "' . $result['forma_pago'] . '",        
          "total": "' . $monto_envio . '",
          "totalSinIgv": "' . $precio_unitario . '",
          "igv": "' . $igv . '",
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
          "descripcionItem":"' . $result["producto"] . '",
          "cantidadItems": "' . $result["cantidad"] . '",
          "tipoDoc": "03",
          "desTipoDoc": "boleta",
          "serie": "' . $result["serie"] . '",
          "numero": "' . $result["correlativo"] . '",
          "fechaEmision": "' . date("Y-m-d", strtotime($result['fecha_reg'])) . '",
          "horaEmision": "' . date("H:i:s", strtotime($result['fecha_reg'])) . '",
          "codServicio": "E",
          "desServicio": "Encomienda",
          "moneda": "PEN",
          "codTipoDocCliente": "1",
          "desCodTipoDocCliente": "DNI",
          "nombreRazonCliente":  "' . $result['razon_social'] . '",
          "dniRucCliente":  "' . $result['numero_documento'] . '",
          "tipoPago": "' . $result['tipo_forma_pago'] . '",
          "desTipoPago": "' . $result['forma_pago'] . '",
          "total": "' . $monto_envio . '",
          "totalSinIgv": "' . $precio_unitario . '",
          "igv": "' . $igv . '",

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
            $estado = 0;
            if ($cod == '001') {
                $estado = 1;
                $respuesta = 'Enviado';
                Utils::getVentasFacturaUp(
                        $result["id_ventas_factura"], $estado
                );
            } else if ($cod == '666') {
                $estado = 2;
                $respuesta = 'No Enviado';
                Utils::getVentasFacturaUp(
                        $result["id_ventas_factura"], $estado
                );
            } else if ($cod == '1071') {
                $estado = 3;
                $respuesta = 'Documento ya informado a sunat';
                Utils::getVentasFacturaUp(
                        $result["id_ventas_factura"], $estado
                );
            }

            $transaction->commit();
        } else {
            $tarifagenerada = 0;
        }

        //  return $cod; 
        /* $estado=3;
          $respuesta = '';
          if ($estado == 1) {
          $respuesta = 'Enviado';
          } else if ($estado == 2) {
          $respuesta = 'No Enviado';
          } else if ($estado == 3) {
          $respuesta = 'Documento ya informado a sunat';
          } */
        return $estado;
    }

    public function actionNotaCredito() {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();
            $tipoDocumentoc = '';
            if ($post['serie_doc'] == 'F004') {
                $tipoDocumentoc = "01";
            } else if ($post['serie_doc'] == 'B005') {
                $tipoDocumentoc = "03";
            }

            $correlativo = '';
            $serie = '';
            if ($post['tipo_comprobante'] == 1) {
                $serie = '006';
                $correlativo = Utils::getGenerarNumeroFB('guia_ventas', $serie);
            } else if ($post['tipo_comprobante'] == 2) {
                $serie = '007';
                $correlativo = Utils::getGenerarNumeroFB('guia_ventas', $serie);
            }


            try {

                $consulta_tpd = \app\models\TipoDocumentos::find()->where(["tipo_doc_sunat" => $post['tipo_doc_cliente']])->one();
                $consulta_movs = \app\models\CodigosNotaCredito::find()->where(["codigo" => $post['tipo_nota_cred']])->one();

                $notas_credito = new \app\models\NotasCredito();
                $notas_credito->fecha_emision = $post['fechaemision'];
                $notas_credito->serie = $serie;
                $notas_credito->correlativo = $correlativo;
                $notas_credito->codigo_motivo_nota = $post['tipo_nota_cred'];
                $notas_credito->motivo_n_credito = $consulta_movs->descripcion;
                $notas_credito->hora = Utils::getHoraActual();
                $notas_credito->documento_electronico_aplicar = $post['serie_doc'] . '-' . $post['correlativo'];
                $notas_credito->cod_tipo_doc_cliente = $post['tipo_doc_cliente'];
                $notas_credito->doc_cliente = $post['numero_doc'];
                $notas_credito->tipo_documento_c = $tipoDocumentoc;
                $notas_credito->des_tipo_doc = $consulta_tpd->documento;
                $notas_credito->nombre_razon_cliente = $post['nombre_razon_cliente'];
                $notas_credito->total = $post['total'];

                $notas_credito->id_usuario_reg = Yii::$app->user->getId();
                $notas_credito->fecha_reg = Utils::getFechaActual();
                $notas_credito->ipmaq_reg = Utils::obtenerIP();


                if (!$notas_credito->save()) {
                    Utils::show($notas_credito->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }

                $curl = curl_init();
                if ($notas_credito->id_notas_credito > 0) {

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://147.182.244.87:8080/facturacionempresas/creditNote/send',
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
                  "fechaEmision": "' . $post['fechaemision'] . '",
                  "codServicio": "N",
                  "desServicio": "Nota Crédito",
                  "descMotivoNotaCredito": "' . $consulta_movs->descripcion . '",
                  "codigoMotivoNota":"' . $post['tipo_nota_cred'] . '",
                  "tipoDoc": "07",
                  "serie": "' . $serie . '",
                  "numero":"' . $correlativo . '",
                  "horaEmision": "' . Utils::getHoraActual() . '",
                  "desTipoDoc": "Nota de Crédito",
                  "codTipoDocCliente": "' . $post['tipo_doc_cliente'] . '",
                  "desCodTipoDocCliente": "' . $consulta_tpd->documento . '",
                  "nombreRazonCliente":"' . $post['nombre_razon_cliente'] . '",
                  "dniRucCliente": "' . $post['numero_doc'] . '",
                  "total": "' . $post['total'] . '",
		          "totalSinIgv": "' . $post['total'] . '",
	              "igv": "0.00", 
                  "moneda": "PEN",
                  "documentoElectronicoAplicar" : "' . $post['serie_doc'] . '-' . $post['correlativo'] . '",
                  "tipoDocumentoAplicar" : "' . $tipoDocumentoc . '"
                  }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                            )
                    );
                    //  $response = '001';
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    $cod = "";
                    $mensaje = "";
                    foreach ($response as $k => $r) {
                        $cod = $response->cod;
                        $mensaje = $response->mensaje;
                    }


                    //    $tarifagenerada = 0; 
                    //  $cod = '1071';

                    if ($cod == '001') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 1;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '666') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 2;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '1071') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 3;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();
                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    }
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            $transaction->commit();
            Utils::jsonEncode($notas_credito->id_notas_credito);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionNotaCreditoF() {

        $id = $_POST["id"];
        $tipo_nota_cred = $_POST["tipo_nota_cred"];
        $fechaemision = $_POST["fechaemision"];
        $numero_doc = $_POST["numero_doc"];
        $correlativod = $_POST["correlativo"];
        $tipo_doc_cliente = $_POST["tipo_doc_cliente"];
        $serie_doc = $_POST["serie_doc"];
        $nombre_razon_cliente = $_POST["nombre_razon_cliente"];
        $total = $_POST["total"];
        $tipo_comprobante = $_POST["tipo_comprobante"];
        $id_guia_ventas = $_POST["id_guia_ventas"];


        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaFactura(:idFactura)');
            $command->bindValue(':idFactura', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();
            $tipoDocumentoc = '';
            $serie_doctp = '';
            if ($serie_doc == '004') {
                $tipoDocumentoc = "01";
                $serie_doctp = 'F' . $serie_doc;
            } else if ($serie_doc == '005') {
                $tipoDocumentoc = "03";
                $serie_doctp = 'B' . $serie_doc;
            }

            $correlativo = '';
            $serie = '';
            if ($tipo_comprobante == 'Boleta') {
                $serie = '006';
                $correlativo = Utils::getGenerarNumeroFB('guia_ventas', $serie);
            } else if ($tipo_comprobante == 'Factura') {
                $serie = '007';
                $correlativo = Utils::getGenerarNumeroFB('guia_ventas', $serie);
            }


            try {

                $consulta_tpd = \app\models\TipoDocumentos::find()->where(["id_tipo_documento" => $tipo_doc_cliente])->one();
                $consulta_movs = \app\models\CodigosNotaCredito::find()->where(["codigo" => $tipo_nota_cred])->one();

                $notas_credito = new \app\models\NotasCredito();
                $notas_credito->fecha_emision = $fechaemision;
                $notas_credito->serie = $serie;
                $notas_credito->correlativo = $correlativo;
                $notas_credito->codigo_motivo_nota = $tipo_nota_cred;
                $notas_credito->motivo_n_credito = $consulta_movs->descripcion;
                $notas_credito->hora = Utils::getHoraActual();
                $notas_credito->documento_electronico_aplicar = $serie_doctp . '-' . $correlativod;
                $notas_credito->cod_tipo_doc_cliente = $consulta_tpd->tipo_doc_sunat;
                $notas_credito->doc_cliente = $numero_doc;
                $notas_credito->tipo_documento_c = $tipoDocumentoc;
                $notas_credito->des_tipo_doc = $consulta_tpd->documento;
                $notas_credito->nombre_razon_cliente = $nombre_razon_cliente;
                $notas_credito->total = $total;
                $notas_credito->id_guia_venta = $id_guia_ventas;
                $notas_credito->id_ventas_factura = $id;
                $notas_credito->cantidad = $result["cantidad"];

                $notas_credito->id_usuario_reg = Yii::$app->user->getId();
                $notas_credito->fecha_reg = Utils::getFechaActual();
                $notas_credito->ipmaq_reg = Utils::obtenerIP();


                if (!$notas_credito->save()) {
                    Utils::show($notas_credito->getErrors(), true);
                    throw new Exception("No se puede guardar datos guia remision");
                }


                $curl = curl_init();
                if ($notas_credito->id_notas_credito > 0) {

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://147.182.244.87:8080/facturacionempresas/creditNote/send',
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
                  "fechaEmision": "' . $fechaemision . '",
                  "codServicio": "N",
                  "desServicio": "Nota Crédito",
                  "descMotivoNotaCredito": "' . $consulta_movs->descripcion . '",
                  "codigoMotivoNota":"' . $tipo_nota_cred . '",
                  "tipoDoc": "07",
                  "serie": "' . $serie . '",
                  "numero":"' . $correlativo . '",
                  "horaEmision": "' . Utils::getHoraActual() . '",
                  "desTipoDoc": "Nota de Crédito",
                  "codTipoDocCliente": "' . $consulta_tpd->tipo_doc_sunat . '",
                  "desCodTipoDocCliente": "' . $consulta_tpd->documento . '",
                  "nombreRazonCliente":"' . $nombre_razon_cliente . '",
                  "dniRucCliente": "' . $numero_doc . '",
                  "total": "' . $total . '",
		          "totalSinIgv": "' . $total. '",
	              "igv": "0.00",
                  "moneda": "PEN",
                  "documentoElectronicoAplicar" : "' . $serie_doctp . '-' . $correlativod . '",
                  "tipoDocumentoAplicar" : "' . $tipoDocumentoc . '"
                  }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                            )
                    );
                    //  $response = '001';
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    $cod = "";
                    $mensaje = "";
                    foreach ($response as $k => $r) {
                        $cod = $response->cod;
                        $mensaje = $response->mensaje;
                    }


                    //    $tarifagenerada = 0;
                    //  $cod = '1071';

                    if ($cod == '001') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 1;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                        $guiaventas = \app\models\GuiaVenta::findOne($id_guia_ventas);
                        $guiaventas->flg_factura = 0;
                        $guiaventas->id_usuario_act = Yii::$app->user->getId();
                        $guiaventas->fecha_act = Utils::getFechaActual();
                        $guiaventas->ipmaq_act = Utils::obtenerIP();

                        if (!$guiaventas->save()) {
                            Utils::show($guiaventas->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '666') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 2;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }

                        $guiaventas = \app\models\GuiaVenta::findOne($id_guia_ventas);
                        $guiaventas->flg_factura = 0;
                        $guiaventas->id_usuario_act = Yii::$app->user->getId();
                        $guiaventas->fecha_act = Utils::getFechaActual();
                        $guiaventas->ipmaq_act = Utils::obtenerIP();

                        if (!$guiaventas->save()) {
                            Utils::show($guiaventas->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '1071') {

                        $upfac = \app\models\NotasCredito::findOne($notas_credito->id_notas_credito);
                        $upfac->estado = 3;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();
                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    }
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            $transaction->commit();
            Utils::jsonEncode($notas_credito->id_notas_credito);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }


    public function actionNotaCreditoFv() {

        $id = $_POST["id"];       


        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call ConsultaNotasCredito(:idNotaCredito)');
            $command->bindValue(':idNotaCredito', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();            


            try {
 
                $curl = curl_init();
                

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://147.182.244.87:8080/facturacionempresas/creditNote/send',
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
                  "fechaEmision": "' . Utils::getFechaaActual() . '",
                  "codServicio": "N",
                  "desServicio": "Nota Crédito",
                  "descMotivoNotaCredito": "' . $result["motivo_n_credito"] . '",
                  "codigoMotivoNota":"' .$result["codigo_motivo_nota"]. '",
                  "tipoDoc": "07",
                  "serie": "' . $result["serienc"] . '",
                  "numero":"' . $result["correlnc"]. '",
                  "horaEmision": "' . Utils::getHoraActual() . '",
                  "desTipoDoc": "Nota de Crédito",
                  "codTipoDocCliente": "' .$result["cod_tipo_doc_cliente"]. '",
                  "desCodTipoDocCliente": "' . $result["des_tipo_doc"] . '",
                  "nombreRazonCliente":"' . $result["nombre_razon_cliente"]. '",
                  "dniRucCliente": "' .$result["doc_cliente"]. '",
                  "total": "' .$result["total_monto"] . '",
		          "totalSinIgv": "' . $result["total_monto"]. '",
	              "igv": "0.0",
                  "moneda": "PEN",
                  "documentoElectronicoAplicar" : "' . $result["documento_electronico_aplicar"].'",
                  "tipoDocumentoAplicar" : "' . $result["tipo_documento_c"] . '"
                  }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                            )
                    );
                    //  $response = '001';
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    $cod = "";
                    $mensaje = "";
                    foreach ($response as $k => $r) {
                        $cod = $response->cod;
                        $mensaje = $response->mensaje;
                    }
                    
                 /*   print_r($mensaje);
                    die();
*/
                    //    $tarifagenerada = 0;
                    //  $cod = '1071';

                    if ($cod == '001') {

                        $upfac = \app\models\NotasCredito::findOne($id);
                        $upfac->estado = 1;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                        $guiaventas = \app\models\GuiaVenta::findOne($upfac->id_guia_venta);
                        $guiaventas->flg_factura = 0;
                        $guiaventas->id_usuario_act = Yii::$app->user->getId();
                        $guiaventas->fecha_act = Utils::getFechaActual();
                        $guiaventas->ipmaq_act = Utils::obtenerIP();

                        if (!$guiaventas->save()) {
                            Utils::show($guiaventas->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '666') {

                        $upfac = \app\models\NotasCredito::findOne($id);
                        $upfac->estado = 2;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();

                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    } else if ($cod == '1071') {

                        $upfac = \app\models\NotasCredito::findOne($id);
                        $upfac->estado = 3;
                        $upfac->id_usuario_act = Yii::$app->user->getId();
                        $upfac->fecha_act = Utils::getFechaActual();
                        $upfac->ipmaq_act = Utils::obtenerIP();
                        if (!$upfac->save()) {
                            Utils::show($upfac->getErrors(), true);
                            throw new Exception("No se puede guardar datos guia remision");
                        }
                    }
                
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            $transaction->commit();
            Utils::jsonEncode($id);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

}
