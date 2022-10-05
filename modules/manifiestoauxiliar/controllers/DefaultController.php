<?php

namespace app\modules\manifiestoauxiliar\controllers;

use yii\web\Controller;
use Yii;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\helpers\Url;

/**
 * Default controller for the `manifiestoauxiliar` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public $enableCsrfValidation = false;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $usuario = Yii::$app->user->getId();
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listamanifiestoAuxiliar(:row,:length,:buscar,:id_auxiliar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':id_auxiliar', $usuario);
            $result = $command->queryAll();
            // $row_cnt = $result->num_rows;
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $data[] = [
                "fecha" => $row['fecha'],
                "placa" => $row['placa'],
                "usuario" => $row['usuario'],
                "cliente" => $row['cliente'],
                "remitente" => $row['remitente'],
                "accion" => '<button class="btn btn-icon btn-light-success btn-sm mr-2" id="btn-guardar" onclick="funcionDescargarExcel(' . '\'' . $row["cliente"] . '\',' . $row["id_cliente"] . ',\'' . $row["fecha"] . '\',' . $row["id_vehiculo"] . ',\'' . $row["serie"] . '\'' . ')"><i class="icon-xl fas fa-file-excel"></i></button>'
                    //.
//'<button class="btn btn-icon btn-light-grey btn-sm mr-2" id="btn-guardar" onclick="funcionManifiesto(' . '\'' . $row["cliente"] . '\',' . $row["id_cliente"] . ',\'' . $row["fecha"] . '\',' . $row["id_vehiculo"] . ',\'' . $row["serie"] . '\'' . ')"><i class="icon-xl fas fa-file-excel"></i></button>'
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
        $id_remitente = $_POST['idCliente'];
        $id_vehiculo = $_POST['id_vehiculo'];
        $razon_social = $_POST['cliente'];
        $serie = $_POST['serie'];


        $data = \app\modules\manifiestoauxiliar\query\ConsultasManifiesto::getImprimirExcel($id_remitente, $fecha, $id_vehiculo, $serie);


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
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
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

        $sheet->setCellValue('B4', 'REMITENTE:');
        $sheet->setCellValue('C4', $razon_social);

        $sheet->setCellValue('E4', 'FECHA:');
        $sheet->setCellValue('F4', $fecha);


        $sheet->setCellValue('A6', 'ITEM');
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



        $i = 7;
        foreach ($data as $k => $v) {


            $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['destino']);
            $sheet->setCellValue('C' . $i, $v['destinatario']);
            $sheet->setCellValue('D' . $i, $v['numero_guia']);
            $sheet->setCellValue('E' . $i, $v['guia_cliente']);
            $sheet->setCellValue('F' . $i, $v['cantidad']);
            $sheet->setCellValue('G' . $i, $v['peso']);
            $sheet->setCellValue('H' . $i, $v['unidad_medida']);
            $sheet->setCellValue('I' . $i, $v['via']);
            $sheet->setCellValue('J' . $i, $v['transportista']);
            $sheet->setCellValue('K' . $i, $v['factura_transportista']);
            $sheet->setCellValue('L' . $i, $v['guia_remision_transportista']);
            $sheet->setCellValue('M' . $i, $v['importe_transportista']);

            $i++;
            $a = 22;
            if ($i > 22) {
                $a = $a + $i;
                $sheet->setCellValue('D' . $a, 'CHOFER:');
                $sheet->setCellValue('E' . $a, $v['conductor']);


                $a = $a + 1;
                $sheet->setCellValue('E' . $a, $v['auxiliar']);
                $sheet->setCellValue('D' . $a, 'AUXILIAR:');
                $sheet->setCellValue('K' . $a, 'RESPONSABLE DE OPERACIONES');
                $a = $a + 1;
                $sheet->setCellValue('E' . $a, $v['placa']);
                $sheet->setCellValue('D' . $a, 'PLACA:');
            } else {
                $sheet->setCellValue('D' . $a, 'CHOFER:');
                $sheet->setCellValue('E' . $a, $v['conductor']);
                $sheet->setCellValue('K' . $a, '__________________________');

                $a = $a + 1;
                $sheet->setCellValue('E' . $a, $v['auxiliar']);
                $sheet->setCellValue('D' . $a, 'AUXILIAR:');
                $sheet->setCellValue('K' . $a, 'RESPONSABLE DE OPERACIONES');
                $a = $a + 1;
                $sheet->setCellValue('E' . $a, $v['placa']);
                $sheet->setCellValue('D' . $a, 'PLACA:');
            }
            $firma = 22;
            if ($i > 22) {
                $sheet->setCellValue('K' . $firma, '__________________________');
                $firma = $firma + 1;
                $sheet->setCellValue('K' . $firma, 'RESPONSABLE DE OPERACIONES');
            } else {
                $sheet->setCellValue('K' . $firma, '__________________________');
                $firma = $firma + 1;
                $sheet->setCellValue('K' . $firma, 'RESPONSABLE DE OPERACIONES');
            }
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
