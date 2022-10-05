<?php

namespace app\modules\direcciones\controllers;

use app\modules\guiaremision\query\Consultas;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
//models
use app\models\Direcciones;
use app\models\Entidades;
use app\models\Ubigeos;

/**
 * Default controller for the `direcciones` module
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

    public function actionGetModal() {
        $entidad = Entidades::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "entidad" => $entidad,
            "ubigeos" => $ubigeos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrear() {
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $entidad = Entidades::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
                    "entidad" => $entidad,
                    "ubigeos" => $ubigeos,
        ]);
    }

    public function actionCreate() {
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

    public function actionGetModalEdit($id) {
        $entidad = Entidades::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $data = Direcciones::findOne($id);

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "direcciones" => $data,
            "entidad" => $entidad,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $direcciones = Direcciones::findOne($post['id_direccion']);
                $direcciones->id_entidad = $post['entidad'];
                $direcciones->id_ubigeo = $post['ubigeos'];
                $direcciones->direccion = $post['direccion'];
                $direcciones->urbanizacion = $post['urbanizacion'];
                $direcciones->referencias = $post['referencias'];
                $direcciones->id_usuario_act = Yii::$app->user->getId();
                $direcciones->fecha_act = Utils::getFechaActual();
                $direcciones->ipmaq_act = Utils::obtenerIP();

                if (!$direcciones->update()) {
                    Utils::show($direcciones->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

           Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $direcciones->id_direccion;


          //  echo json_encode($direcciones->id_direccion);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $direcciones = Direcciones::findOne($post['id_direccion']);
                $direcciones->id_usuario_del = Yii::$app->user->getId();
                $direcciones->fecha_del = Utils::getFechaActual();
                $direcciones->ipmaq_del = Utils::obtenerIP();

                if (!$direcciones->save()) {
                    Utils::show($direcciones->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro direcciones");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
          //  echo json_encode($direcciones->id_direccion);
           Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $direcciones->id_direccion;

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
            $command = Yii::$app->db->createCommand('call listadoDirecciones(:row,:length,:buscar)');
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
                "direccion" => $row['direccion'],
                "nombre_provincia" => $row['nombre_provincia'],
                "nombre_distrito" => $row['nombre_distrito'],
                "urbanizacion" => $row['urbanizacion'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_direccion"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_direccion"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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
 public function actionExportarDirecciones(){
     $data = Consultas::exportarDirecciones();


     $filename = "totalDirecciones.xlsx";

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
     $sheet->setCellValue('C2', 'TOTAL DIRECCIONES');
     $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
     // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
     // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
     //    $drawing->setCoordinates('A1');

     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

     $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
     $drawing->setCoordinates('A1');

     $sheet->getStyle('A6:H6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
     $sheet->getStyle('A6:H6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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
     $sheet->setCellValue('B6', 'id_direccion');
     $sheet->setCellValue('C6', 'razon_social');
     $sheet->setCellValue('D6', 'direccion');
     $sheet->setCellValue('E6', 'urbanizacion');
     $sheet->setCellValue('F6', 'nombre_provincia');
     $sheet->setCellValue('G6', 'nombre_distrito');
     $sheet->setCellValue('H6', 'total');

     $i = 7;
     foreach ($data as $k => $v) {



         $sheet->setCellValue('A' . $i, $k + 1);
         $sheet->setCellValue('B' . $i, $v['id_direccion']);
         $sheet->setCellValue('C' . $i, $v['razon_social']);
         $sheet->setCellValue('D' . $i, $v['direccion']);
         $sheet->setCellValue('E' . $i, $v['urbanizacion']);
         $sheet->setCellValue('F' . $i, $v['nombre_provincia']);
         $sheet->setCellValue('G' . $i, $v['nombre_distrito']);
         $sheet->setCellValue('H' . $i, $v['total']);



         $i++;
     }

     $sheet->getStyle('A6' . ':H' . $i)->applyFromArray($styleBorder);

     foreach (range('A', 'H') as $columnID) {
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
