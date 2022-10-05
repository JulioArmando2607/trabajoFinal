<?php

namespace app\modules\liquidacion\controllers;

use app\models\Entidades;
use app\models\Personas;
use app\models\User;
use app\modules\persona\Persona;
use Yii;
use yii\web\Controller;
use app\components\Utils;
use app\models\ClientesVentas;
use app\models\Agente;
use app\modules\guiaventas\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\FormaPago;
use app\models\TipoComprobante;
use app\models\TipoDocumentos;
use app\models\TipoEntrega;

//models
use app\models\Ubigeos;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\models\Liquidacion;
use app\models\GuiaRemision;

/**
 * Default controller for the `liquidacion` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLiquidar($id, $fecha)
    {

        $a = explode("/", $fecha);
        $idEntidad = count($a);
        //$mesc = $a[1];

        return $this->render('liquidar', [
            "idEntidad" => $id,
            "mesc" => $fecha
        ]);
    }

    public function actionCrear($id)
    {
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        // $via = Via::find()->where(["fecha_del" => null])->all();
        //  $via_ = Via::find()->where(["fecha_del" => null])->one();
        // $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $via_->id_via])->all();

        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = \app\modules\guiaremision\query\Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
            // "via" => $via,
            "rem_des_client" => $rem_des_client,
            "agente" => $agente,
            "conductor" => $conductor,
            "vehiculo" => $vehiculo,
            "producto" => $producto,
            "tipoCarga" => $tipoCarga,
            //   "via_tipo" => $via_tipo,
            "transportista" => $transportista,
            "entidad" => $id
        ]);
    }

    public function actionMesLiquidado($id)
    {

        return $this->render('mesliquidado', [
            "idEntidad" => $id
        ]);
    }

    public function actionListarMesLiquidado()
    {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        //$fechaInicio = empty($_POST["query"]["fechaInicio"]) ? '' : $_POST["query"]["fechaInicio"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $mes = empty($_POST["query"]["mes"]) ? '' : $_POST["query"]["mes"];


        $a = explode("/", $mes);
        $idEntidad = $a[0];
        $mesc = $a[1] . "-00";

        // $entidad = $a[2];
        // $total_registro = 0;
        $result = null;

        //3
        //
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            $command = Yii::$app->db->createCommand('call listadoliquidacion(:row,:length,:busca,:idCliente, :fechal, @total)');

            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':busca', $buscar);
            $command->bindValue(':idCliente', $idEntidad);
            $command->bindValue(':fechal', $mesc);

            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $total = 0;
        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_liquidacion"] . ')"><i class="flaticon-edit"></i></button>' .
                '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminar(' . $row["id_liquidacion"] . ')"><i class="flaticon-delete"></i></button>';

            $data[] = [
                "numero_guia" => $row['guia_pegaso'],
                "fecha" => date("d/m", strtotime($row['fecha'])),
                "guia_cliente" => $row['guia_cliente'],

                "origen" => $row['origen'],
                "destino" => $row['destino'],
                "bultos" => $row['bultos'],
                "peso" => $row['peso'],
                "DESCD" => $row['tipo_carga'],
                "VIA" => $row['nombre_via'],
                "tarifa_base" => $row['tarifa_base'],
                "TARIFA_PROVINCIA" => $row['tarifa_provincia_kg_adicional'],
                "peso_exceso" => $row['peso_exceso'],
                "estado" => $row['estado'],
                "reembarque" => $row['reembarque'],
                "costo" => $row['costo'],
              //  "accion" => $botones,
            ];
        }

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => 100,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $liquidacion = \app\models\Liquidacion::findOne($post["id"]);
                $liquidacion->id_usuario_del = Yii::$app->user->getId();
                $liquidacion->fecha_del = Utils::getFechaActual();
                $liquidacion->ipmaq_del = Utils::obtenerIP();

                if (!$liquidacion->save()) {
                    Utils::show($liquidacion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $liquidacion->id_liquidacion;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id)
    {
        //  $data = Via::findOne($id);
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call ConsultaLiquidacion(:idLiquidacion)');
            $command->bindValue(':idLiquidacion', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "dato" => $result
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionLiquidart()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = 'holas';
    }

    public function actionLiquidacion()
    {

//        $result = null;
//        try {
//
//            $command = Yii::$app->db->createCommand('call guardarLiquidacion(:entidad, :fechainicio ,:fechafin,:userReg, :imaqReg)');
//            $command->bindValue(':entidad', $_POST['idEntidad']);
//            $command->bindValue(':fechainicio', $_POST['fechaInicio']);
//            $command->bindValue(':fechafin', $_POST['fecha_fin']);
//            $command->bindValue(':userReg', Yii::$app->user->getId());
//            $command->bindValue(':imaqReg', Utils::obtenerIP());
//            $result = $command->queryOne();
//        } catch (\Exception $e) {
//            echo "Error al ejecutar procedimiento" . $e;
//        }

        $result = [];
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            $command = Yii::$app->db->createCommand('call ListaLiquidacionEntidad(:row,:entidad,:length,:busca,:fechainicio, :fechafin, @total, @totalmonto)');

            $command->bindValue(':row', 0);
            $command->bindValue(':length', 10000);
            $command->bindValue(':busca', '');
            $command->bindValue(':fechainicio', $_POST['fechaInicio']);
            $command->bindValue(':fechafin', $_POST['fecha_fin']);
            $command->bindValue(':entidad', $_POST['idEntidad']);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        foreach ($result as $row) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $liquidacion = new Liquidacion();
                $liquidacion->id_guia_remision = $row["id_guia_remision"];
                $liquidacion->fecha = $row["FECHA"];
                $liquidacion->guia_pegaso = $row["GUIA_PEGASO"];
                $liquidacion->id_guia_cliente = $row["id_guia_remision_cliente"];
                //$liquidacion->id_guia_cliente = $row["FECHA"];
                $liquidacion->guia_cliente = $row["GUIA_CLIENTE"];
                $liquidacion->origen = $row["ORIGEN"];
                $liquidacion->destino = $row["DESTINO"];
                $liquidacion->tarifa_provincia_kg_adicional = $row["TARIFA_PROVINCIA"];
                $liquidacion->bultos = $row["BULTOS"];
                $liquidacion->peso = $row["PESO"];
                $liquidacion->id_tipo_carga = $row["id_tipo_carga"];
                $liquidacion->id_via = $row["id_via"];
                $liquidacion->tarifa_base = $row["TARIFA_BASE"];
                $liquidacion->peso_exceso = $row["PESO_EXCESO"];
                $liquidacion->reembarque = $row["REEMBARQUE"];
                $liquidacion->costo = $row["totalsuma"];

                $liquidacion->id_tarifa = $row["id_tarifa"];
                $liquidacion->id_tarifa_provincia_ent = $row["id_tarifa_provincia_ent"];


                //  $liquidacion->id_estado_guia = $row["id_estado"];
                $liquidacion->id_usuario_reg = Yii::$app->user->getId();
                $liquidacion->fecha_reg = Utils::getFechaActual();
                $liquidacion->ipmaq_reg = Utils::obtenerIP();

                if (!$liquidacion->save()) {
                    Utils::show($liquidacion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }


                $guia_remiesion = GuiaRemision::findOne($row["id_guia_remision"]);
                $guia_remiesion->flg_liquidacion = 1;

                if (!$guia_remiesion->save()) {
                    Utils::show($guia_remiesion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = true;
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $liquidacion = \app\models\Liquidacion::findOne($post["id"]);
                $liquidacion->reembarque = $post["reembarque"];
                $liquidacion->costo = $post["costoLiquidacion"];
                $liquidacion->observacion = $post["obs_reembarque"];
                $liquidacion->peso = $post["peso_liquidacion"];
                $liquidacion->peso_exceso = $post["peso_exceso_liquidacion"];
                $liquidacion->id_usuario_act = Yii::$app->user->getId();
                $liquidacion->fecha_act = Utils::getFechaActual();
                $liquidacion->ipmaq_act = Utils::obtenerIP();

                if (!$liquidacion->save()) {
                    Utils::show($liquidacion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $liquidacion->id_liquidacion;
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
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoEntidadesT(:row,:length,:buscar)');
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
                "razon_social" => $row['razon_social'],
                "numero_documento" => $row['numero_documento'],
                "accion" => '<a class="btn btn-sm btn-light-primary font-weight-bold mr-2" href="liquidacion/default/crear/' . $row["id_entidad"] . '"><i class="flaticon-search"></i></a>'
                    . '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEnviarCorreo(' . $row["id_entidad"] . ')"><i class="flaticon-delete"></i></button>' .
                    '<a class="btn btn-sm btn-light-info font-weight-bold mr-2" href="liquidacion/default/mes-liquidado/' . $row["id_entidad"] . '"><i class="flaticon-calendar"></i></a>',
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

    public function actionListasLiquidar()
    {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $fechaInicio = empty($_POST["query"]["fechaInicio"]) ? '' : $_POST["query"]["fechaInicio"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        // $a = explode("/", $fechaInicio);
        //  $fechaini = $a[0];
        // $fechafin = $a[1];
        // $entidad = $a[2];
        // $total_registro = 0;
        $result = null;

        //3
        //    die(); 
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            $command = Yii::$app->db->createCommand('call ListaLiquidacion(:row,:length,:busca,@total'
                // .
                // :entidad,:busca,:fechainicio, :fechafin, @total, @totalmonto'
                . ')');

            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':busca', $buscar);
            /*     $command->bindValue(':fechainicio', '2021-05-20');
              $command->bindValue(':fechafin', '2021-11-20');
              $command->bindValue(':entidad', 711); */
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $total = 0;
        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_liquidacion"] . ')"><i class="flaticon-edit"></i></button>' .
                '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminar(' . $row["id_liquidacion"] . ')"><i class="flaticon-delete"></i></button>';

            $data[] = [
                "numero_guia" => $row['guia_pegaso'],
                "fecha" => date("d/m", strtotime($row['fecha'])),
                "guia_cliente" => $row['guia_cliente'],
                "origen" => $row['origen'],
                "destino" => $row['destino'],
                "bultos" => $row['bultos'],
                "peso" => $row['peso'],
                "DESCD" => $row['tipo_carga'],
                "VIA" => $row['nombre_via'],
                "tarifa_base" => $row['tarifa_base'],
                "TARIFA_PROVINCIA" => $row['tarifa_provincia_kg_adicional'],
                "peso_exceso" => $row['peso_exceso'],
                "estado" => $row['estado'],
                "reembarque" => $row['reembarque'],
                "costo" => $row['costo'],
                "accion" => $botones,
            ];
        }

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => 100,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionListas()
    {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $fechaInicio = empty($_POST["query"]["fechaInicio"]) ? '' : $_POST["query"]["fechaInicio"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $a = explode("/", $fechaInicio);
        $fechaini = $a[0];
        $fechafin = $a[1];
        $entidad = $a[2];
        $total_registro = 0;
        $result = null;

        //3
        //    die(); 
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            $command = Yii::$app->db->createCommand('call ListaLiquidacionEntidad(:row,:entidad,:length,:busca,:fechainicio, :fechafin, @total, @totalmonto)');

            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':busca', $buscar);
            $command->bindValue(':fechainicio', $fechaini);
            $command->bindValue(':fechafin', $fechafin);
            $command->bindValue(':entidad', $entidad);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $total = 0;
        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="liquidacion/default/imprimirv/' . $row["GUIA_PEGASO"] . '"><i class="icon-2x flaticon-doc"></i></a>';

            $data[] = [
                "numero_guia" => $row['GUIA_PEGASO'],
                "fecha" => date("d/m", strtotime($row['FECHA'])),
                "guia_cliente" => $row['GUIA_CLIENTE'],
                "origen" => $row['ORIGEN'],
                "destino" => $row['DESTINO'],
                "bultos" => $row['BULTOS'],
                "peso" => $row['PESO'],
                "DESCD" => $row['DESCD'],
                "VIA" => $row['VIA'],
                "TARIFA_BASE" => $row['TARIFA_BASE'],
                "TARIFA_PROVINCIA" => $row['TARIFA_PROVINCIA'],
                "PESO_EXCESO" => $row['PESO_EXCESO'],
                "estado" => $row['estado_guia_pegaso'],
                //"TARIFA_EXCESO" => $row['TARIFA_EXCESO'],
                "REEMBARQUE" => $row['REEMBARQUE'],
                "costo" => $row['costo'],
                "accion" => $botones,
            ];
        }

        $json_data = [
            "data" => $data,
            "meta" => [
                "page" => $page,
                "pages" => $pages,
                "perpage" => 100,
                "sort" => "asc",
                "total" => $total_registro
            ]
        ];

        ob_start();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $json_data;
    }

    public function actionExportarLiquidacion($id)
    {

        $data = \app\modules\liquidacion\query\Consultas::getLiquidacion($id);


        $filename = "Liquidacion.xlsx";

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
        $sheet->setCellValue('C2', 'LIQUIDACIÓN DE MERCADERÍA');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));

        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:R6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:R6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'FECHA');
        $sheet->setCellValue('c6', 'GUÍA PEGASO');
        $sheet->setCellValue('D6', 'GUÍA CLIENTE');
        $sheet->setCellValue('E6', 'ORIGEN');
        $sheet->setCellValue('F6', 'DESTINO');
        $sheet->setCellValue('G6', 'BULTOS');
        $sheet->setCellValue('H6', 'PESO');
        $sheet->setCellValue('I6', 'DESCD');
        $sheet->setCellValue('J6', 'VIA');
        $sheet->setCellValue('K6', 'TARIFA BASE');
        $sheet->setCellValue('L6', 'TARIFA AEREA');
        $sheet->setCellValue('M6', 'PESO EXCESO');
        $sheet->setCellValue('N6', 'TARIFA EXCESO');
        $sheet->setCellValue('O6', 'REEMBARQUE');
        $sheet->setCellValue('P6', 'SUB TOTAL');
        $sheet->setCellValue('Q6', 'IGV');
        $sheet->setCellValue('R6', 'TOTAL');


        $i = 7;
        foreach ($data as $k => $v) {

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['FECHA']);
            $sheet->setCellValue('C' . $i, $v['GUIA_PEGASO']);
            $sheet->setCellValue('D' . $i, $v['GUIA_CLIENTE']);
            $sheet->setCellValue('E' . $i, $v['ORIGEN']);
            $sheet->setCellValue('F' . $i, $v['DESTINO']);
            $sheet->setCellValue('G' . $i, $v['BULTOS']);
            $sheet->setCellValue('H' . $i, $v['PESO']);
            $sheet->setCellValue('I' . $i, $v['DESCD']);
            $sheet->setCellValue('J' . $i, $v['VIA']);
            $sheet->setCellValue('K' . $i, $v['TARIFA_BASE']);
            $sheet->setCellValue('L' . $i, $v['TARIFA_AEREA']);
            $sheet->setCellValue('M' . $i, $v['PESO_EXCESO']);
            $sheet->setCellValue('N' . $i, $v['TARIFA_EXCESO']);
            $sheet->setCellValue('O' . $i, $v['REEMBARQUE']);
            $sheet->setCellValue('P' . $i, $v['SUBTOTAL']);
            $sheet->setCellValue('Q' . $i, $v['IGV']);
            $sheet->setCellValue('R' . $i, $v['TOTAL']);

            $i++;
        }

        $sheet->getStyle('A6' . ':R' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'R') as $columnID) {
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

    public function actionCalcular()
    {
        $fechaInicio = $_POST["fechaInicio"];
        $fecha_fin = $_POST["fecha_fin"];
        $idEntidad = $_POST["idEntidad"];

        $totalesLiquidacion = \app\modules\liquidacion\query\Consultas::getTotalesLiquidacion($idEntidad, $fechaInicio, $fecha_fin);


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $totalesLiquidacion;
    }

    public static function actionMail()
    {
        $idEntidad = $_POST["idEntidad"];
        $mesc = $_POST["fechali"];
        setlocale(LC_TIME, "spanish");
        $mes = Utils::getMes(date("m", strtotime($mesc)));
        $usuario = User::findOne(Yii::$app->user->getId());
        $nombresPersona = Personas::find()->where(["fecha_del" => null, "id_persona" => $usuario->id_persona])->one();
        $nombres = $nombresPersona->nombres . " " . $nombresPersona->apellido_paterno . " " . $nombresPersona->apellido_materno;
        $entidad = Entidades::findOne($idEntidad);

        $final = false;

        $mensaje = "Estimado " . $entidad->razon_social . "<br><br>Adjunto liquidacion correspondiente al mes de " . $mes . " <br><br> Quedamos atentos a cualquier consulta <br><br><br>
             Atentamente, <br> $nombres <br> <br>  ".

            '<html><body> <img src="http://147.182.244.87/pegaso/web/assets/3091f183/media/logos/pegasologo.png" width="300" height="100" class="max-h-30px" alt="" /> </body>  </html> ';

        try {

            $correo = 'armandojulio82@gmail.com;administracion@pegasoserviceexpress.com';
            $correo_clientes = explode(";", $correo);
            $mail = Yii::$app->mailer->compose()
                ->setFrom('administracion@pegasoserviceexpress.com')
                //->setTo($correo)
                ->setSubject('LIQUIDACION ' . $entidad->razon_social)
                //  ->setTo('armandojulio82@gmail.com', 'administracion@pegasoserviceexpress.com')
                ->setHtmlBody($mensaje)
                ->attachContent(self::exportMesLiquidado($idEntidad, $mesc), ['fileName' => 'Liquidacion.xlsx', 'contentType' => 'application/vnd.ms-excel']);
               // ->send();

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
    }

    public function actionCalcularli()
    {
        $idEntidad = $_POST["idEntidad"];

        $totalesLiquidacion = \app\modules\liquidacion\query\Consultas::getTotalesLiquiD($idEntidad);


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $totalesLiquidacion;
    }

    public function actionLiquidarLiquidacion()
    {

        $result = [];
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            $command = Yii::$app->db->createCommand('call ListaLiquidacion(:row,:length,:busca,@total)');

            $command->bindValue(':row', 0);
            $command->bindValue(':length', 10000);
            $command->bindValue(':busca', '');

            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        foreach ($result as $row) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $liquidacion = Liquidacion::findOne($row["id_liquidacion"]);

                $liquidacion->flg_liquidado = 1;
                $liquidacion->id_usuario_act = Yii::$app->user->getId();
                $liquidacion->fecha_act = Utils::getFechaActual();
                $liquidacion->ipmaq_act = Utils::obtenerIP();

                if (!$liquidacion->save()) {
                    Utils::show($liquidacion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }


                $guia_remiesion = GuiaRemision::findOne($row["id_guia_remision"]);
                $guia_remiesion->flg_liquidacion = 1;

                if (!$guia_remiesion->save()) {
                    Utils::show($guia_remiesion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = true;
    }

    public function actionLiquidacionEntidad($id)
    {
        //$idEntidad = $_POST[""];

        $data = \app\modules\liquidacion\query\Consultas::getLiquidacionExport($id);

        $totales = \app\modules\liquidacion\query\Consultas::getTotalesLiquiD($id);

        $filename = "Liquidacion.xlsx";

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
        $sheet->setCellValue('C2', 'LIQUIDACIÓN DE MERCADERÍA');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));

        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:R6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:R6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'FECHA');
        $sheet->setCellValue('c6', 'GUÍA PEGASO');
        $sheet->setCellValue('D6', 'GUÍA CLIENTE');
        $sheet->setCellValue('E6', 'ORIGEN');
        $sheet->setCellValue('F6', 'DESTINO');
        $sheet->setCellValue('G6', 'ESTADO GUIA');
        $sheet->setCellValue('H6', 'VIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'TIPO CARGA');
        $sheet->setCellValue('L6', 'TARF PROV.');
        $sheet->setCellValue('M6', 'TARIFA BASE');
        $sheet->setCellValue('N6', 'PESO EXCESO');
        $sheet->setCellValue('O6', 'REEMBARQUE');
        $sheet->setCellValue('P6', 'COSTO');
        $sheet->setCellValue('Q6', 'OBSERVACION');
        //   $sheet->setCellValue('R6', 'TOTAL');


        $i = 7;
        foreach ($data as $k => $v) {

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['fecha']);
            $sheet->setCellValue('C' . $i, $v['guia_pegaso']);
            $sheet->setCellValue('D' . $i, $v['guia_cliente']);
            $sheet->setCellValue('E' . $i, $v['origen']);
            $sheet->setCellValue('F' . $i, $v['destino']);
            $sheet->setCellValue('G' . $i, $v['estado']);
            $sheet->setCellValue('H' . $i, $v['nombre_via']);
            $sheet->setCellValue('I' . $i, $v['bultos']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['tipo_carga_nombre']);
            $sheet->setCellValue('L' . $i, $v['tarifa_provincia_kg_adicional']);
            $sheet->setCellValue('M' . $i, $v['tarifa_base']);
            $sheet->setCellValue('N' . $i, $v['peso_exceso']);
            $sheet->setCellValue('O' . $i, $v['reembarque']);
            $sheet->setCellValue('P' . $i, $v['costo']);
            $sheet->setCellValue('Q' . $i, $v['observacion']);
            /*  $sheet->setCellValue('R' . $i, $v['TOTAL']); */
            $sheet->setCellValue('P' . ($i + 1), $totales['totalsuma']);
            $sheet->setCellValue('P' . ($i + 2), $totales['igv']);
            $sheet->setCellValue('P' . ($i + 3), $totales['total']);

            $sheet->setCellValue('O' . ($i + 1), 'SUBTOTAL');
            $sheet->setCellValue('O' . ($i + 2), 'IGV');
            $sheet->setCellValue('O' . ($i + 3), 'TOTAL');

            // $sheet->setCellValue('P' .( $i + 1), 'hola');
            //$sheet->setCellValue('O' .( $i + 1), 'hola');
            //     $sheet->setCellValue('N' .( $i + 1), 'hola');
            $i++;
        }
        //$totales
        $sheet->getStyle('A6' . ':R' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'R') as $columnID) {
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

    public function actionCalcularMesLi()
    {
        $idEntidad = $_POST["idEntidad"];
        $fecha_liquidacion = $_POST["fecha_liquidacion"];
        // $idEntidad = $_POST["idEntidad"];

        $totalesLiquidacion = \app\modules\liquidacion\query\Consultas::getTotalesLiquidado($fecha_liquidacion . "-00", $idEntidad);


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $totalesLiquidacion;
    }

    public function actionExportMesLiquidado()
    {
        $idCliente = $_POST["idEntidad"];
        $fechal = $_POST["fecha_liquidacion"];
        $data = \app\modules\liquidacion\query\Consultas::getExportLiquidado($idCliente, $fechal . "-00");

        $totales = \app\modules\liquidacion\query\Consultas::getTotalesLiquidado($fechal . "-00", $idCliente);

        $filename = "Liquidacion.xlsx";

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
        $sheet->setCellValue('C2', 'LIQUIDACIÓN DE MERCADERÍA');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));

        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:R6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:R6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'FECHA');
        $sheet->setCellValue('c6', 'GUÍA PEGASO');
        $sheet->setCellValue('D6', 'GUÍA CLIENTE');
        $sheet->setCellValue('E6', 'ORIGEN');
        $sheet->setCellValue('F6', 'DESTINO');
        $sheet->setCellValue('G6', 'ESTADO GUIA');
        $sheet->setCellValue('H6', 'VIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'TIPO CARGA');
        $sheet->setCellValue('L6', 'TARF PROV.');
        $sheet->setCellValue('M6', 'TARIFA BASE');
        $sheet->setCellValue('N6', 'PESO EXCESO');
        $sheet->setCellValue('O6', 'REEMBARQUE');
        $sheet->setCellValue('P6', 'OBSERVACION');
        $sheet->setCellValue('Q6', 'COSTO');
        //   $sheet->setCellValue('R6', 'TOTAL');


        $i = 7;
        foreach ($data as $k => $v) {

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['fecha']);
            $sheet->setCellValue('C' . $i, $v['guia_pegaso']);
            $sheet->setCellValue('D' . $i, $v['guia_cliente']);
            $sheet->setCellValue('E' . $i, $v['origen']);
            $sheet->setCellValue('F' . $i, $v['destino']);
            $sheet->setCellValue('G' . $i, $v['estado']);
            $sheet->setCellValue('H' . $i, $v['nombre_via']);
            $sheet->setCellValue('I' . $i, $v['bultos']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['tipo_carga_nombre']);
            $sheet->setCellValue('L' . $i, $v['tarifa_provincia_kg_adicional']);
            $sheet->setCellValue('M' . $i, $v['tarifa_base']);
            $sheet->setCellValue('N' . $i, $v['peso_exceso']);
            $sheet->setCellValue('O' . $i, $v['reembarque']);
            $sheet->setCellValue('P' . $i, $v['costo']);
            $sheet->setCellValue('Q' . $i, $v['observacion']);
            /*  $sheet->setCellValue('R' . $i, $v['TOTAL']); */
            $sheet->setCellValue('P' . ($i + 1), $totales['totalsuma']);
            $sheet->setCellValue('P' . ($i + 2), $totales['igv']);
            $sheet->setCellValue('P' . ($i + 3), $totales['total']);

            $sheet->setCellValue('O' . ($i + 1), 'SUBTOTAL');
            $sheet->setCellValue('O' . ($i + 2), 'IGV');
            $sheet->setCellValue('O' . ($i + 3), 'TOTAL');

            // $sheet->setCellValue('P' .( $i + 1), 'hola');
            //$sheet->setCellValue('O' .( $i + 1), 'hola');
            //     $sheet->setCellValue('N' .( $i + 1), 'hola');
            $i++;
        }
        //$totales
        $sheet->getStyle('A6' . ':R' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'R') as $columnID) {
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

    public static function exportMesLiquidado($idCliente, $fechal)
    {
        $data = \app\modules\liquidacion\query\Consultas::getExportLiquidado($idCliente, $fechal);

        $totales = \app\modules\liquidacion\query\Consultas::getTotalesLiquidado($fechal, $idCliente);

        $filename = "Liquidacion.xlsx";

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
        $sheet->setCellValue('C2', 'LIQUIDACIÓN DE MERCADERÍA');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));

        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:R6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:R6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);

        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);

        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'FECHA');
        $sheet->setCellValue('c6', 'GUÍA PEGASO');
        $sheet->setCellValue('D6', 'GUÍA CLIENTE');
        $sheet->setCellValue('E6', 'ORIGEN');
        $sheet->setCellValue('F6', 'DESTINO');
        $sheet->setCellValue('G6', 'ESTADO GUIA');
        $sheet->setCellValue('H6', 'VIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'TIPO CARGA');
        $sheet->setCellValue('L6', 'TARF PROV.');
        $sheet->setCellValue('M6', 'TARIFA BASE');
        $sheet->setCellValue('N6', 'PESO EXCESO');
        $sheet->setCellValue('O6', 'REEMBARQUE');
        $sheet->setCellValue('P6', 'COSTO');
        $sheet->setCellValue('Q6', 'OBSERVACION');
        //   $sheet->setCellValue('R6', 'TOTAL');


        $i = 7;
        foreach ($data as $k => $v) {

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['fecha']);
            $sheet->setCellValue('C' . $i, $v['guia_pegaso']);
            $sheet->setCellValue('D' . $i, $v['guia_cliente']);
            $sheet->setCellValue('E' . $i, $v['origen']);
            $sheet->setCellValue('F' . $i, $v['destino']);
            $sheet->setCellValue('G' . $i, $v['estado']);
            $sheet->setCellValue('H' . $i, $v['nombre_via']);
            $sheet->setCellValue('I' . $i, $v['bultos']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['tipo_carga_nombre']);
            $sheet->setCellValue('L' . $i, $v['tarifa_provincia_kg_adicional']);
            $sheet->setCellValue('M' . $i, $v['tarifa_base']);
            $sheet->setCellValue('N' . $i, $v['peso_exceso']);
            $sheet->setCellValue('O' . $i, $v['reembarque']);
            $sheet->setCellValue('P' . $i, $v['costo']);
            $sheet->setCellValue('Q' . $i, $v['observacion']);
            /*  $sheet->setCellValue('R' . $i, $v['TOTAL']); */
            $sheet->setCellValue('P' . ($i + 1), $totales['totalsuma']);
            $sheet->setCellValue('P' . ($i + 2), $totales['igv']);
            $sheet->setCellValue('P' . ($i + 3), $totales['total']);

            $sheet->setCellValue('O' . ($i + 1), 'SUBTOTAL');
            $sheet->setCellValue('O' . ($i + 2), 'IGV');
            $sheet->setCellValue('O' . ($i + 3), 'TOTAL');

            // $sheet->setCellValue('P' .( $i + 1), 'hola');
            //$sheet->setCellValue('O' .( $i + 1), 'hola');
            //    $sheet->setCellValue('N' .( $i + 1), 'hola');
            $i++;
        }
        //$totales
        $sheet->getStyle('A6' . ':R' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $drawing->setWorksheet($sheet);

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $response = Yii::$app->getResponse();
//        $headers = $response->getHeaders();
//        $headers->set('Content-Type', 'application/vnd.ms-excel');
//        $headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');

        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }

    public function actionCalculoTarifa()
    {

        $idLiquidacion = $_POST['id'];
        $peso_liquidacion = $_POST['peso_liquidacion'];
        $calcula = \app\modules\liquidacion\query\Consultas::getCalculoTarifa($idLiquidacion, $peso_liquidacion);


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $calcula;
    }
}
