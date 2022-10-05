<?php
namespace app\modules\pedidoclienteop\controllers;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\pedidoclienteop\query\Consultas;
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
 * Default controller for the `pedidoclienteop` module
 */
class DefaultController extends Controller {

    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'reg-direccion',
                            'crear',
                            'visualizar',
                            'buscar-direccion',
                            'create',
                            'editar',
                            'update',
                            'delete',
                            'lista',
                            'crear-direccion',
                            'exportar',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionRegDireccion() {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call mostarEntidad(:idUsuario)');
            $command->bindValue(':idUsuario', Yii::$app->user->getId());
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearDireccion", [
            "ubigeos" => $ubigeos,
            "entidades" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call mostarEntidad(:idUsuario)');
            $command->bindValue(':idUsuario', Yii::$app->user->getId());
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
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
                    "rem_des_client" => $rem_des_client,
                    "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "ubigeos" => $ubigeos,
                    "areas" => $areas,
                    "tipo_unidad" => $tipo_unidad,
                    "entidades" => $result
        ]);
    }

    public function actionVisualizar($id) {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call ConsultaPedidos(:id_pedido)');
            $command->bindValue(':id_pedido', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        return $this->render('visualizar', [
                    "pedidosCliente" => $result,
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
                $pedidosClientes = new \app\models\PedidoCliente();
                $pedidosClientes->id_remitente = $post["entidades"];
                 $pedidosClientes->id_cliente = $post["entidades_cliente"];
                
                // $numas=$numero_+Yii::$app->user->getId() ;
                $pedidosClientes->nm_solicitud = Utils::getGenerarNumero("PEDIDOS_CLIENTE");
                $pedidosClientes->fecha = $post["fecha"];
                $pedidosClientes->hora_recojo = $post["hora"];
                $pedidosClientes->tipo_servicio = $post["tipo_servicio"];
                //  $pedidosClientes->id_distrito = $post["distrito"];
                $pedidosClientes->id_direccion_recojo = $post["direccion"];
                $pedidosClientes->contacto = $post["contacto"];
                $pedidosClientes->id_area = $post["area"];
                $pedidosClientes->referencia = $post["referencia"];
                $pedidosClientes->telefono = $post["telefono"];
                $pedidosClientes->cantidad_personal = $post["cantidad_personas"];
                $pedidosClientes->id_tipo_unidad = $post["tipo_unidad"];
                $pedidosClientes->stoka = $post["stoka"];
                $pedidosClientes->fragil = $post["fragil"];
                $pedidosClientes->cantidad = $post["cantidad"];
                $pedidosClientes->peso = $post["peso"];
                $pedidosClientes->alto = $post["alto"];
                $pedidosClientes->ancho = $post["ancho"];
                $pedidosClientes->largo = $post["largo"];
                $pedidosClientes->estado_mercaderia = $post["esta_listo"];
                $pedidosClientes->observacion = $post["observacion"];
                $pedidosClientes->notificacion = $post["notificacion_"];
                $pedidosClientes->notificacion_descarga = $post["notificacion_descarga"];
                
                $pedidosClientes->id_estado = Utils::PENDIENTE;
                $pedidosClientes->id_usuario_reg = Yii::$app->user->getId();
                $pedidosClientes->fecha_reg = Utils::getFechaActual();
                $pedidosClientes->ipmaq_reg = Utils::obtenerIP();

                if (!$pedidosClientes->save()) {
                    Utils::show($pedidosClientes->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }



                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($pedidosClientes->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $pedidosClientes->id_pedido_cliente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditar($id) {
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call ConsultaPedidos(:id_pedido)');
            $command->bindValue(':id_pedido', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $guia = GuiaRemision::findOne($id);
        $via = Via::find()->where(["fecha_del" => null])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
         $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
       
         $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        $areas = \app\models\Areas::find()->where(["fecha_del" => null])->all();
        $tipo_unidad = \app\models\TipoUnidad::find()->where(["fecha_del" => null])->all();
        $direcciones = \app\models\Direcciones::find()->where(["fecha_del" => null,"id_entidad" => $result["id_cliente"] ])->all();
         return $this->render('editar', [
                    "pedidosCliente" => $result,
                    "guia" => $guia,
                    "via" => $via,
              "rem_des_client" => $rem_des_client,
                    // "agente" => $agente,
                    "conductor" => $conductor,
                    "vehiculo" => $vehiculo,
                    "producto" => $producto,
                    "tipoCarga" => $tipoCarga,
                    "ubigeos" => $ubigeos,
                    "areas" => $areas,
                    "tipo_unidad" => $tipo_unidad,
                    "direcciones" => $direcciones
        ]);
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $pedidosClientes = \app\models\PedidoCliente::findOne($post["id_pedido_cliente"]);
                //   $pedidosClientes = GuiaRemision::findOne($post["id_guia"]);
                $pedidosClientes->fecha = $post["fecha"];
                $pedidosClientes->hora_recojo = $post["hora"];
                $pedidosClientes->tipo_servicio = $post["tipo_servicio"];
                $pedidosClientes->id_remitente = $post["remitente"];

                $pedidosClientes->id_direccion_recojo = $post["direccion"];
                $pedidosClientes->contacto = $post["contacto"];
                $pedidosClientes->id_area = $post["area"];
                $pedidosClientes->referencia = $post["referencia"];
                $pedidosClientes->telefono = $post["telefono"];
                $pedidosClientes->cantidad_personal = $post["cantidad_personas"];
                $pedidosClientes->id_tipo_unidad = $post["tipo_unidad"];
                $pedidosClientes->stoka = $post["stoka"];
                $pedidosClientes->fragil = $post["fragil"];
                $pedidosClientes->cantidad = $post["cantidad"];
                $pedidosClientes->peso = $post["peso"];
                $pedidosClientes->alto = $post["alto"];
                $pedidosClientes->ancho = $post["ancho"];
                $pedidosClientes->largo = $post["largo"];
                $pedidosClientes->estado_mercaderia = $post["esta_listo"];
                $pedidosClientes->observacion = $post["observacion"];
                $pedidosClientes->notificacion = $post["notificacion_"];
                $pedidosClientes->notificacion_descarga = $post["notificacion_descarga"];
//                $pedidosClientes->id_estado = Utils::PENDIENTE;
                $pedidosClientes->id_usuario_act = Yii::$app->user->getId();
                $pedidosClientes->fecha_act = Utils::getFechaActual();
                $pedidosClientes->ipmaq_act = Utils::obtenerIP();

                if (!$pedidosClientes->save()) {
                    Utils::show($pedidosClientes->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($pedidosClientes->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $pedidosClientes->id_pedido_cliente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $pedidosClientes = \app\models\PedidoCliente::findOne($post["id_pedido_cliente"]);
                $pedidosClientes->id_usuario_del = Yii::$app->user->getId();
                $pedidosClientes->fecha_del = Utils::getFechaActual();
                $pedidosClientes->ipmaq_del = Utils::obtenerIP();

                if (!$pedidosClientes->save()) {
                    Utils::show($pedidosClientes->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $pedidosClientes->id_pedido_cliente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
      //  $usuario = Yii::$app->user->getId();

        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadorPedidoOp(:row,:length,:buscar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
           // $command->bindValue(':usuario_session', $usuario);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = [];
        foreach ($result as $k => $row) {

            $botones = ' <a  class="btn btn-icon btn-light-primary mr-2" href="pedidoclienteop/default/visualizar/' . $row["id_pedido_cliente"] . '"><i class="flaticon-eye"></i></a>'
                    . ' <button class="btn btn-icon btn-light-danger  mr-2" onclick="funcionEliminar(' . $row["id_pedido_cliente"] . ')"><i class="flaticon-delete"></i></button>';

            if ($row["nombre_estado"] == "PENDIENTE") {
                $botones .= ' <a class="btn btn-icon btn-light-success mr-2" href="pedidoclienteop/default/editar/' . $row["id_pedido_cliente"] . '"><i class="flaticon-edit"></i></a>';
            }

            $data[] = [
                "nm_solicitud" => $row['nm_solicitud'],
                "fecha" => $row['fecha'],
                "hora_recojo" => $row['hora_recojo'],
                //"tipo_servicio" => $row['tipo_servicio'],
                "tipo_servicios" => $row['tipo_servicios'],
                "nombre_estado" => $row['nombre_estado'],
                "cliente"=> $row['cliente'],
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

    public function actionCrearDireccion() {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $direcciones = new Direcciones();

                $direcciones->id_entidad = $post['entidad'];
                $direcciones->id_ubigeo = $post['ubigeos'];
                $direcciones->direccion = $post['direccion'];
                $direcciones->urbanizacion = $post['urbanizacion'];
                $direcciones->referencias = $post['referencias'];
                $direcciones->flg_estado = Utils::ACTIVO;
                $direcciones->id_usuario_reg = Yii::$app->user->getId();
                $direcciones->fecha_reg = Utils::getFechaActual();
                $direcciones->ipmaq_reg = Utils::obtenerIP();

                if (!$direcciones->save()) {
                    Utils::show($direcciones->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($direcciones->id_direccion);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $direcciones->id_direccion;
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

}
