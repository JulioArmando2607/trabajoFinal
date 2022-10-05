<?php

namespace app\modules\reportetecnocom\controllers;

use app\components\Utils;
use app\models\Entidades;
use app\modules\reportetecnocom\query\Consultas;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
/**
 * Default controller for the `reportetecnocom` module
 */
class DefaultController extends Controller
{
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
    /**
     * Renders the index view for the module
     * @return string
     */
    public $enableCsrfValidation = false;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $estado = \app\models\Estados::find()->where(["fecha_del" => null])->all();
        $tipoEnvio = \app\models\Via::find()->where(["fecha_del" => null])->all();
        return $this->render('index', [
            "rem_des_client" => $rem_des_client,
            "estados"=>$estado,
            "tipoEnvio" =>$tipoEnvio

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
        $via = $a[2];
        $estado = $a[3];
        $total_registro = 0;
        $result = null;

        try {

            $command = Yii::$app->db->createCommand('call ReporteTecnocom(:row,:length,@total,:fechainicio,:fechafin,:estado, :via)');

            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':fechainicio', $fechaini);
            $command->bindValue(':fechafin', $fechafin);
            $command->bindValue(':estado', $estado);
           $command->bindValue(':via', $via);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }


        $total = 0;
        $data = [];
        foreach ($result as $k => $row) {
         //   $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="liquidacion/default/imprimirv/' . $row["numero_solicitud"] . '"><i class="icon-2x flaticon-doc"></i></a>';

            $data[] = [
                "fecha_salida" => $row['FECHA_SALIDA'],
                "guia_rem_pegaso" => $row['GUIA_REM_PEGASO'],
                "guia_rem_tecnocom" => $row['GUIA_REM_TECNOCOM'],
                "bulto" => $row['BULTO'],
                "peso" => $row['PESO'],
                "destino" => $row['DESTINO'],
                "consigando" => $row['CONSIGNADO'],
                "tipo_envio" => $row['TIPO_ENVIO'],
                "emp_transporte" => $row['EMP_TRANSPORTES'],
                "n_factura" =>$row['N_FACTURA'],
                "guia_transportista" => $row['GUIA_TRANSPORTISTA'],
                "fecha_entrega" => $row['FECHA_ENTREGA'],


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
        $via = $_POST['via'];

        $mes = Utils::getMes(date("m", strtotime($fechaInicio)));

        $data =  Consultas::getReporteExcelTecnocom($fechaInicio, $fecha_fin, $estado, $via);


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
        $sheet->mergeCells("D1:D4");

        $sheet->setCellValue('D1', 'TECNOCOM');
        $sheet->getStyle('D1')->applyFromArray(['font' => ['bold' => true, 'size' => 30], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $sheet->mergeCells("E1:G2");
        $sheet->setCellValue('E1', 'DESPACHO A PROVINCIA');
        $sheet->getStyle('E1')->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $sheet->mergeCells("E3:F3");
        $sheet->setCellValue('E3', 'DIA');
        $sheet->getStyle('E3')->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $sheet->mergeCells("E4:F4");
        $sheet->setCellValue('E4', 'MES');
        $sheet->getStyle('E4')->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $sheet->setCellValue('G3', $fechaInicio);
        $sheet->getStyle('G3')->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $sheet->setCellValue('G4', strtoupper($mes));
        $sheet->getStyle('G4')->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);


     //   $sheet->mergeCells("C2:L2");
      //  $sheet->setCellValue('C2', 'TOTAL GUIAS');
       // $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

        //$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        // $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/SistemaPegaso/modules/manifiestoventa/assets/images/logo.jpeg');
        // $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:M6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:M6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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
        $sheet->setCellValue('B6', 'FECHA_SALIDA');
        $sheet->setCellValue('C6', 'GUIA_REM_PEGASO');
        $sheet->setCellValue('D6', 'GUIA_REM_TECNOCOM');
        $sheet->setCellValue('E6', 'BULTO');
        $sheet->setCellValue('F6', 'PESO');
        $sheet->setCellValue('G6', 'DESTINO');
        $sheet->setCellValue('H6', 'CONSIGNADO');
        $sheet->setCellValue('I6', 'TIPO_ENVIO');
        $sheet->setCellValue('J6', 'EMP_TRANSPORTES');
        $sheet->setCellValue('K6', 'N_FACTURA');
        $sheet->setCellValue('L6', 'GUIA_TRANSPORTISTA');
        $sheet->setCellValue('M6', 'FECHA_ENTREGA');



        $i = 7;
        foreach ($data as $k => $v) {


            $fecha = ($v['FECHA_SALIDA'] == null) ? '-' : date("d/m/Y", strtotime($v['FECHA_SALIDA']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['FECHA_SALIDA']);
            $sheet->setCellValue('C' . $i, $v['GUIA_REM_PEGASO']);
            $sheet->setCellValue('D' . $i, $v['GUIA_REM_TECNOCOM']);
            $sheet->setCellValue('E' . $i, $v['BULTO']);
            $sheet->setCellValue('F' . $i, $v['PESO']);
            $sheet->setCellValue('G' . $i, $v['DESTINO']);
            $sheet->setCellValue('H' . $i, $v['CONSIGNADO']);
            $sheet->setCellValue('I' . $i, $v['TIPO_ENVIO']);
            $sheet->setCellValue('J' . $i, $v['EMP_TRANSPORTES']);
            $sheet->setCellValue('K' . $i, $v['N_FACTURA']);
            $sheet->setCellValue('L' . $i, $v['GUIA_TRANSPORTISTA']);
            $sheet->setCellValue('M' . $i, $v['FECHA_ENTREGA']);

            //  $sheet->setCellValue('Q' . $i, $v['factura_transportista']);


            $i++;
        }

        $sheet->getStyle('A6' . ':Q' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        //$drawing->setWorksheet($sheet);

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
