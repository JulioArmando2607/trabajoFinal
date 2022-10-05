<?php

namespace app\modules\reporteguiasrem\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
 
use app\models\Entidades;
use app\components\Utils;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\helpers\Url;
/**
 * Default controller for the `reporteguiasrem` module
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
                            'lista',
                            'exportar'

                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex() {
         $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
         $estado = \app\models\Estados::find()->where(["fecha_del" => null])->all();
             return $this->render('index', [
                 "rem_des_client" => $rem_des_client,
                 "estados"=>$estado
                   
        ]);
       
    }

    public function actionLista() {
 
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
        $estado = $a[3];
        $total_registro = 0;
        $result = null;
    
    
        //3
        //    die(); 
        try {
            //IN row1 INT,IN entidad INT,IN length1 INT,IN busca varchar(200),IN fechainicio DATE,IN fechafin DATE,OUT total int, OUT totalmonto int
            //:row,:entidad,:length,:busca,:fechainicio, :fechafin, @total, @totalmonto
            $command = Yii::$app->db->createCommand('call ResporteGuiasDetalle(:row,:length,@total,:fechainicio,:fechafin,:entidad,:estado)');

              $command->bindValue(':row', $row);
              $command->bindValue(':length', $length);
              $command->bindValue(':fechainicio', $fechaini);
              $command->bindValue(':fechafin', $fechafin);
              $command->bindValue(':estado', $estado);              
              $command->bindValue(':entidad', $entidad);  
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
       

        $total = 0;
        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="liquidacion/default/imprimirv/' . $row["numero_solicitud"] . '"><i class="icon-2x flaticon-doc"></i></a>';
          
            $data[] = [
                "numero_solicitud" => $row['numero_solicitud'],
                "numero_guia" => $row['numero_guia'],
                "fecha_emision" => date("d/m/Y", strtotime($row['fecha_emision'])),
                "cliente" => $row['cliente'],
                "cliente_origen" => $row['cliente_origen'],
                "direccion_origen" => $row['direccion_origen'],
                "ciudad_origen" => $row['ciudad_origen']."/".$row['depart_origen'],
//                "depart_origen" => /
                "cliente_destino" => $row['cliente_destino'],
                "direccion_destino" => $row['direccion_destino'],
                "ciudad_destino" => $row['ciudad_destino']."/".$row['depart_destino'],
                //"depart_destino" => 
                "via" => $row['via'],
                "tipo_servicio" => $row['tipo_servicio'],
                "fecha_entrega" => $row['fecha_entrega'],
                "estado" => $row['nombre_estado'],
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

    public function actionExportar() {

 
        $estado = $_POST['estado'];
        $fechaInicio = $_POST['fechaInicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $cliente = $_POST['cliente'];
        


        $data = \app\modules\reporteguiasrem\query\Consultas::getReporteGuias($fechaInicio, $fecha_fin, $cliente, $estado);
 
     //   $data = Consultas::getImprimirExcel();


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

        //$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

       // $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/SistemaPegaso/modules/manifiestoventa/assets/images/logo.jpeg');
       // $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:Q6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:Q6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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
        $sheet->setCellValue('B6', 'Numero Solicitud');
        $sheet->setCellValue('C6', 'Fecha Emision');
        $sheet->setCellValue('D6', 'Cliente');
        $sheet->setCellValue('E6', 'Numero de Guia');
        $sheet->setCellValue('F6', 'cliente_origen');
        $sheet->setCellValue('G6', 'direccion_origen');
        $sheet->setCellValue('H6', 'ciudad_origen');
        $sheet->setCellValue('I6', 'depart_origen');
        $sheet->setCellValue('J6', 'cliente_destino');
        $sheet->setCellValue('K6', 'direccion_destino');
        $sheet->setCellValue('L6', 'ciudad_destino');
        $sheet->setCellValue('M6', 'depart_destino');
        $sheet->setCellValue('N6', 'via');
        $sheet->setCellValue('O6', 'tipo_servicio');
        $sheet->setCellValue('P6', 'nombre_estado');
         $sheet->setCellValue('Q6', 'fecha_entrega');
    
 

        $i = 7;
        foreach ($data as $k => $v) {
 

           // $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['numero_solicitud']);
            $sheet->setCellValue('C' . $i, $v['fecha_emision']);
            $sheet->setCellValue('D' . $i, $v['cliente']);
            $sheet->setCellValue('E' . $i, $v['numero_guia']);
            $sheet->setCellValue('F' . $i, $v['cliente_origen']);
            $sheet->setCellValue('G' . $i, $v['direccion_origen']);
            $sheet->setCellValue('H' . $i, $v['ciudad_origen']);
            $sheet->setCellValue('I' . $i, $v['depart_origen']);
            $sheet->setCellValue('J' . $i, $v['cliente_destino']);
            $sheet->setCellValue('K' . $i, $v['direccion_destino']);
            $sheet->setCellValue('L' . $i, $v['ciudad_destino']);
            $sheet->setCellValue('M' . $i, $v['depart_destino']);
            $sheet->setCellValue('N' . $i, $v['via']);
            $sheet->setCellValue('O' . $i, $v['tipo_servicio']);
            $sheet->setCellValue('P' . $i, $v['nombre_estado']);
            $sheet->setCellValue('Q' . $i, $v['fecha_entrega']);
            
          //  $sheet->setCellValue('Q' . $i, $v['factura_transportista']);
             

            $i++;
        }

        $sheet->getStyle('A6' . ':Q' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
        }

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
