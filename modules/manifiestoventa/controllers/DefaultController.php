<?php

namespace app\modules\manifiestoventa\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Manifiesto;
use app\modules\manifiestoventa\query\ConsultasMVenta;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\helpers\Url;

/**
 * Default controller for the `manifiesto` module
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

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call listamanifiestov(:row,:length,:buscar)');
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
                "fecha" => $row['fecha'],
                "razon_social" => $row['razon_social'],
                "accion" => '<button class="btn btn-icon btn-light-success btn-sm mr-2" id="btn-guardar" onclick="funcionDescargarExcel(' . '\'' . $row["razon_social"] . '\'' . ',\'' . $row["fecha"] . '\'' . ',\'' . $row["serie"] . '\'' . ')"><i class="icon-xl fas fa-file-excel"></i></button>'
                . '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionDescargarCierre(' . '\'' . $row["razon_social"] . '\'' . ',\'' . $row["fecha"] . '\'' . ',\'' . $row["serie"] . '\'' . ')"><i class="icon-xl fas fa-file-excel"></i></button>'
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

    public function actionDesarrollo() {

        // $id = $_POST['id_remitente'];
        $fecha = $_POST['fecha'];
        $razon_social = $_POST['razon_social'];
        $serie = $_POST['serie'];


        $data = ConsultasMVenta::getImprimirExcel($fecha, $serie);


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
        $sheet->setCellValue('C2', 'MANIFIESTO DE SALIDA');
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

        $sheet->setCellValue('B4', 'REMITENTE:');
        $sheet->setCellValue('C4', $razon_social);

        $sheet->setCellValue('E4', 'FECHA:');
        $sheet->setCellValue('F4', $fecha);



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

    public function actionCierre() {

        // $id = $_POST['id_remitente'];
        $fecha = $_POST['fecha'];
        $razon_social = $_POST['razon_social'];
        $serie = $_POST['serie'];
        $cajera = '';

        $data = ConsultasMVenta::getCierre($fecha, $serie);


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
        $sheet->setCellValue('C2', 'CIERRE DE CAJA');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
        //   $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/manifiestoventa/assets/images/logo.jpeg'); // put your path and image here
        // put your path and image here
        $drawing->setCoordinates('A1');

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

        $sheet->setCellValue('B4', 'NOMBRE CAJERA:');
        $sheet->setCellValue('C4', $cajera);

        $sheet->setCellValue('E4', 'FECHA:');
        $sheet->setCellValue('F4', $fecha);



        //    $sheet->setCellValue('A6', 'FECHA REG');
        // $sheet->setCellValue('A6', 'REMITENTE');
        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'N° FACTURA');
        $sheet->setCellValue('C6', 'N° BOLETA');
        $sheet->setCellValue('D6', 'GUIA PEGASO');
        $sheet->setCellValue('E6', 'NOMBRE O RAZON SOCIAL');
        $sheet->setCellValue('F6', 'SUB TOTAL');
        $sheet->setCellValue('G6', 'IGV');
        $sheet->setCellValue('H6', 'TOTAL');
        $sheet->setCellValue('I6', 'EFECTIVO');
        $sheet->setCellValue('J6', 'YAPE');
        $sheet->setCellValue('K6', 'DEPOSITO');
        $sheet->setCellValue('L6', 'TARJETA DE CREDITO');
        $sheet->setCellValue('M6', 'PAGO EN DESTINO');



        $i = 7;
        foreach ($data as $k => $v) {

          

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['N_FACTURA']);
            $sheet->setCellValue('C' . $i, $v['N_BOLETA']);
            $sheet->setCellValue('D' . $i, $v['GUIA_PEGASO']);
            $sheet->setCellValue('E' . $i, $v['NOMBRE_RAZON_SOCIAL']);
            $sheet->setCellValue('F' . $i, $v['SUB_TOTAL']);
            $sheet->setCellValue('G' . $i, $v['IGV']);
            $sheet->setCellValue('H' . $i, $v['TOTAL']);
            $sheet->setCellValue('I' . $i, $v['EFECTIVO']);
            $sheet->setCellValue('J' . $i, $v['YAPE']);
            $sheet->setCellValue('K' . $i, $v['IZIPAY']);
            $sheet->setCellValue('L' . $i, $v['DEPOSITO']);
            $sheet->setCellValue('M' . $i, $v['Contra_Entrega']);


            $i++;
        }

        $sheet->getStyle('A6' . ':M' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'M') as $columnID) {
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
