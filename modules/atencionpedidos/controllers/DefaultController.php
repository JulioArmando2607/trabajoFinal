<?php

namespace app\modules\atencionpedidos\controllers;

use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\atencionpedidos\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use app\models\Ubigeos;

/**
 * Default controller for the `atencionpedidos` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCrear() {
        $via = Via::find()->where(["fecha_del" => null])->all();
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $areas = \app\models\Areas::find()->where(["fecha_del" => null])->all();
        $tipo_unidad = \app\models\TipoUnidad::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
                    "via" => $via,
                    "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "ubigeos" => $ubigeos,
                    "areas" => $areas,
                    "tipo_unidad" => $tipo_unidad
        ]);
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
                $atencionpedidos = new \app\models\PedidoCliente();
                $atencionpedidos->id_cliente = Yii::$app->user->getId();
                $atencionpedidos->fecha = $post["fecha"];
                $atencionpedidos->hora_recojo = $post["hora"];
                $atencionpedidos->tipo_servicio = $post["tipo_servicio"];
                $atencionpedidos->id_distrito = $post["distrito"];
                $atencionpedidos->direccion_recojo = $post["direccion"];
                $atencionpedidos->contacto = $post["contacto"];
                $atencionpedidos->id_area = $post["area"];
                $atencionpedidos->referencia = $post["referencia"];
                $atencionpedidos->telefono = $post["telefono"];
                $atencionpedidos->cantidad_personal = $post["cantidad_personas"];
                $atencionpedidos->id_tipo_unidad = $post["tipo_unidad"];
                $atencionpedidos->stoka = $post["stoka"];
                $atencionpedidos->fragil = $post["fragil"];
                $atencionpedidos->cantidad = $post["cantidad"];
                $atencionpedidos->peso = $post["peso"];
                $atencionpedidos->alto = $post["alto"];
                $atencionpedidos->ancho = $post["ancho"];
                $atencionpedidos->largo = $post["largo"];
                $atencionpedidos->estado_mercaderia = $post["esta_listo"];
                $atencionpedidos->observacion = $post["observacion"];

                $atencionpedidos->id_estado = Utils::PENDIENTE;
                $atencionpedidos->id_usuario_reg = Yii::$app->user->getId();
                $atencionpedidos->fecha_reg = Utils::getFechaActual();
                $atencionpedidos->ipmaq_reg = Utils::obtenerIP();

                if (!$atencionpedidos->save()) {
                    Utils::show($atencionpedidos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }



                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($atencionpedidos->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $atencionpedidos->id_pedido_cliente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditar($id) {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call ConsultaPedidos(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $rest = [];
        try {

            $commands = Yii::$app->db->createCommand('call atePedidos(:id_pedido_cliente)');
            $commands->bindValue(':id_pedido_cliente', $id);
            $rest = $commands->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $guia = GuiaRemision::findOne($id);
        $via = Via::find()->where(["fecha_del" => null])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        $areas = \app\models\Areas::find()->where(["fecha_del" => null])->all();
        $tipo_unidad = \app\models\TipoUnidad::find()->where(["fecha_del" => null])->all();
        $personas = Consultas::getPersona();
        $auxiliar = Consultas::getAuxiliar();

        return $this->render('editar', [
                    "pedidosCliente" => $result,
                    "guia" => $guia,
                    "via" => $via,
                    "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "ubigeos" => $ubigeos,
                    "areas" => $areas,
                    "tipo_unidad" => $tipo_unidad,
                    "atecliente" => $rest,
                    "personas" => $personas,
                    "auxiliar" => $auxiliar
        ]);
    }

    public function actionUpdate($id) {
        $valorval = $id;
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $atencionpedidos = new \app\models\AtencionPedidos();
                //   $atencionpedidos = GuiaRemision::findOne($post["id_guia"]);
                if ($id != $atencionpedidos->id_atencion_pedidos) {
                    $atencionpedidos = \app\models\AtencionPedidos::findOne($post["id_atencion_pedidos"]);
                    //$atencionpedidos->id_pedido_cliente = $post["id_pedido_cliente"]; auxiliar
                    $atencionpedidos->flg_estado = Utils::ASIGNADO;
                    $atencionpedidos->conductor = $post["conductor"];
                    $atencionpedidos->unidad = $post["vehiculo"];
                    $atencionpedidos->auxiliar = $post["auxiliar"];

                    $atencionpedidos->id_usuario_act = Yii::$app->user->getId();
                    $atencionpedidos->fecha_act = Utils::getFechaActual();
                    $atencionpedidos->ipmaq_act = Utils::obtenerIP();

                    if (!$atencionpedidos->save()) {
                        Utils::show($atencionpedidos->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision");
                    }
                    $pedidocliente = \app\models\PedidoCliente::findOne($post["id_pedido_cliente"]);
                    //   $atencionpedidos = GuiaRemision::findOne($post["id_guia"]);
                    $pedidocliente->id_estado = Utils::ASIGNADO;
                    $atencionpedidos->auxiliar = $post["auxiliar"];

                    $pedidocliente->cantidad_personal = $post["cantidad_personas"];
                    $pedidocliente->stoka = $post["stoka"];
                    // $guiaVenta->id_estado = Utils::PENDIENTE;
                    $pedidocliente->id_usuario_act = Yii::$app->user->getId();
                    $pedidocliente->fecha_act = Utils::getFechaActual();
                    $pedidocliente->ipmaq_act = Utils::obtenerIP();
                    if (!$pedidocliente->save()) {
                        Utils::show($pedidocliente->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision");
                    }
                    $this->mail($atencionpedidos->id_atencion_pedidos);
                } else {
                    //  $atencionpedidos = new \app\models\AtencionPedidos();
                    $atencionpedidos->id_pedido_cliente = $post["id_pedido_cliente"];
                    $atencionpedidos->conductor = $post["conductor"];
                    $atencionpedidos->unidad = $post["vehiculo"];
                    //  $atencionpedidos->observacion = $post["observacion"];
                    $atencionpedidos->auxiliar = $post["auxiliar"];
                    $atencionpedidos->flg_estado = Utils::ASIGNADO;
                    $atencionpedidos->id_usuario_reg = Yii::$app->user->getId();
                    $atencionpedidos->fecha_reg = Utils::getFechaActual();
                    $atencionpedidos->ipmaq_reg = Utils::obtenerIP();

                    if (!$atencionpedidos->save()) {
                        Utils::show($atencionpedidos->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision");
                    }
                    $pedidocliente = \app\models\PedidoCliente::findOne($post["id_pedido_cliente"]);
                    //   $atencionpedidos = GuiaRemision::findOne($post["id_guia"]);
                    $pedidocliente->id_estado = Utils::ASIGNADO;
                    $pedidocliente->cantidad_personal = $post["cantidad_personas"];
                    $pedidocliente->stoka = $post["stoka"];

                    $atencionpedidos->auxiliar = $post["auxiliar"];
                    // $guiaVenta->id_estado = Utils::PENDIENTE;
                    $pedidocliente->id_usuario_act = Yii::$app->user->getId();
                    $pedidocliente->fecha_act = Utils::getFechaActual();
                    $pedidocliente->ipmaq_act = Utils::obtenerIP();
                    if (!$pedidocliente->save()) {
                        Utils::show($pedidocliente->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision");
                    }
                    $this->mail($atencionpedidos->id_atencion_pedidos);
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($atencionpedidos->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $atencionpedidos->id_atencion_pedidos;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $atencionpedidos = \app\models\PedidoCliente::findOne($post["id_pedido_cliente"]);
                $atencionpedidos->id_usuario_del = Yii::$app->user->getId();
                $atencionpedidos->fecha_del = Utils::getFechaActual();
                $atencionpedidos->ipmaq_del = Utils::obtenerIP();

                if (!$atencionpedidos->save()) {
                    Utils::show($atencionpedidos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($atencionpedidos->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $atencionpedidos->id_pedido_cliente;
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
            $command = Yii::$app->db->createCommand('call listaPedidosAte(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = [];
        foreach ($result as $k => $row) {

            $botones = ' <a class="btn btn-icon btn-light-success btn-sm mr-2" href="atencionpedidos/default/editar/' . $row["id_pedido_cliente"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["nombre_estado"] == "PENDIENTE") {
                $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminarGuia(' . $row["id_pedido_cliente"] . ')"><i class="flaticon-delete"></i></button>';
            }

            $data[] = [
                "fecha" => $row['fecha'],
                "hora_recojo" => $row['hora_recojo'],
                "usuario" => $row['usuario'],
                "nm_solicitud" => $row['nm_solicitud'],
                "razon_social" => $row['razon_social'],
                "tipo_servicio" => $row['tipo_servicio'],
                "tipo_servicios" => $row['tipo_servicios'],
                "nombre_estado" => $row['nombre_estado'],
                "nombres" => $row['nombres'],
                "auxiliar" => $row['auxiliar'],
                "vehiculo" => $row['vehiculo'],
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionEnviar() {
        $final = false;

        /*     $result = [];
          try {

          $command = Yii::$app->db->createCommand('call ConsultaPedidos(:id_guia)');
          $command->bindValue(':id_guia', $id);
          $result = $command->queryOne();
          } catch (\Exception $e) {
          echo "Error al ejecutar procedimiento" . $e;
          } */
        if (Yii::$app->request->post()) {


            $post = Yii::$app->request->post();

            $mensaje = '<p>'
                    . '<strong>'
                    . '<span style="font-family: Arial, Helvetica, sans-serif; font-size: 18px;">Nombre</span>'
                    . '</strong>'
                    . '</p>'
                    . '<p>'
                    . '<em>' . $post["nombre"] . '</em>'
                    . '</p>'
                    . '<p>'
                    . '<strong><span style="font-size: 18px;">Telefono</span></strong>'
                    . '</p>'
                    . '<p>'
                    . '<em>' . $post["telefono"] . '</em>'
                    . '</p>'
                    . '<p>'
                    . '<strong><span style="font-size: 18px;">Correo</span></strong>'
                    . '</p>'
                    . '<p><a href="' . $post["correo"] . '">'
                    . '<em>' . $post["correo"] . '</em>'
                    . '</a>'
                    . '</p>'
                    . '<p>'
                    . '<strong><span style="font-size: 18px;">Mensaje</span></strong>'
                    . '</p>'
                    . '<p>'
                    . '<em>' . $post["mensaje"] . '</em>'
                    . '</p>';

            try {
                Yii::$app->mailer->compose()
                        ->setFrom('softwarevalue.pe@gmail.com')
                        ->setFrom('')
                        ->setTo('operaciones@pegasoserviceexpress.com')
                        ->setSubject('Contacto Pegaso')
                        ->setHtmlBody($mensaje)
                        //->attach($path)
                        ->send();
                $final = true;
            } catch (Exception $ex) {
                \app\components\Utils::show($ex, true);
                $final = false;
            }

            // echo json_encode($final);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $final;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

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

    public function actionEquipoSiemens() {
        $id = $_POST["id_pedido_c"];
        $detalle_guia = Consultas::getEquipoSiemens($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public static function Mail($id) {


        $pedido = "";
        $conductor = "";
        $auxiliar = "";
        $vehiculo = "";
        $nombre_cliente = "";
        $correo = "";
        $final = false;
        $notificacion = "";
        $notificacion_descarga = ""; 

        $datos_correo = Consultas::getDatosAuxCVh($id);
        $contar = count($datos_correo);
        foreach ($datos_correo as $r) {
            $conductor = $r["conductor"];
            $auxiliar = $r["auxiliar"];
            $vehiculo = $r["vehiculo"];
            $nombre_cliente = $r["nombre_cliente"];
            $pedido = $r["nm_solicitud"];
            $correo = $r["correo"];
            $notificacion = $r["notificacion"];
            $notificacion_descarga = $r["notificacion_descarga"];
        }
        if ($contar >= 1) {
            $tabla = "";


            $mensaje = '<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
    <div
        style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
         
    </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" valign="top" style="font-size:0; padding: 20px;" bgcolor="#0836D6">
                            <div
                                style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                            class="mobile-center">
                                            <img src="https://pegasoserviceexpress.com/logo_pegaso.png" width="250"
                                                style="display: block; border: 0px;" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;"
                            bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bolder; line-height: 24px; padding-top: 25px;">
                                        <h2
                                            style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                            <img data-imagetype="External"
                                                src="https://s3.amazonaws.com/linio-live-transactional/REVAMP/general/icons/icon_tick_green.png"
                                                alt="Icono" width="20" height="20">
                                            ¡Tu pedido ha sido Asignado!
                                        </h2>
                                        <br>
                                        <p>PEDIDO : ' . $pedido . ' </p>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p
                                            style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                            Hola <b> ' . $nombre_cliente . ',</b>
                                            Te informamos que se asigno el siguiente personal para la
                                            atencion.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">

                                            <tr>


                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    Conductor
                                                </td>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    ' . $conductor . '
                                                </td>

                                            </tr>
                                            <tr>

                                            <tr>
                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    Auxiliar
                                                </td>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    ' . $auxiliar . '
                                                </td>

                                            </tr>

                                            <tr>

                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    Vehiculo
                                                </td>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    ' . $vehiculo . '
                                                </td>
                                            </tr>
                                            
                                              <tr>
  
                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    Notificacion 
                                                </td>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    ' . $notificacion . '
                                                </td>
                                            </tr>
                                            
                                               <tr>

                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    Notificacion Descarga
                                                </td>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                    ' . $notificacion_descarga . '
                                                </td>
                                            </tr>


                                </tr>

                            </table>

                        </td>
                    </tr>
                     

        </tr>
        <tr>

        </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td align="center" height="100%" valign="top" width="100%"
            style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                <tr>

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style=" padding: 20px; background-color: #06099b;" bgcolor="#1b9ba3">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 25px 0 15px 0;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7">
                                    <a href="https://pegasoserviceexpress.com/ClientePegaso/web/" target="_blank"
                                        style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #66b3b7; padding: 15px 30px; border: 1px solid #66b3b7; display: block;">Seguimiento
                                        de carga</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#ffffff" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center"
                            style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;">
                            <p style="margin: 0;">Este correo electrónico fue creado y probado con PEGASO SERVICE
                                EXPRESS. <a href="https://pegasoserviceexpress.com/" style="color: #5db3ec;">Quienes
                                    Somos PEGASO SERVICE EXPRESS</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>';

            try {
               
                $correo_clientes = explode(";", $correo);
 
                $mail = Yii::$app->mailer->compose()
                        ->setFrom('seguimiento@pegasoserviceexpress.com')
                        //->setTo($correo)
                        ->setSubject('Pedido N°' . $pedido)
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
//        } else {
//            throw new HttpException(404, 'The requested Item could not be found.');
//        }
        } else {
            echo 0;
        }
    }

}
