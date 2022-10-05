<?php

namespace app\modules\entidades\controllers;

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
use app\models\Entidades;
use app\models\TipoDocumentos;
use app\models\TipoEntidad;
use app\models\Ubigeos;
use app\models\Direcciones;

/**
 * Default controller for the `entidades` module
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
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crear", [
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionBuscarNumeroDoc() {
        $numerog = $_POST["numero"];

        $result = null;
        try {

            $command = Yii::$app->db->createCommand('call consultaNumeroDocumento(:numero_documento)');
            $command->bindValue(':numero_documento', $numerog);

            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionCrear() {
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();

        return $this->render('crear', [
                    "tipo_entidad" => $tipo_entidad,
                    "tipo_documento" => $tipo_documento,
        ]);
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {

                $entidades = new Entidades();
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];
                //$entidades->flg_estado = Utils::ACTIVO;
                $entidades->id_usuario_reg = Yii::$app->user->getId();
                $entidades->fecha_reg = Utils::getFechaActual();
                $entidades->ipmaq_reg = Utils::obtenerIP();

                if (!$entidades->save()) {
                    Utils::show($entidades->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $direccion = new Direcciones();
                $direccion->id_entidad = $entidades->id_entidad;
                $direccion->id_ubigeo = $post['ubigeos'];
                $direccion->direccion = $post['direccion'];
                $direccion->urbanizacion = $post['urbanizacion'];
                $direccion->referencias = $post['referencias'];
                $direccion->flg_estado = Utils::ACTIVO;
                $direccion->id_usuario_reg = Yii::$app->user->getId();
                $direccion->fecha_reg = Utils::getFechaActual();
                $direccion->ipmaq_reg = Utils::obtenerIP();

                if (!$direccion->save()) {
                    Utils::show($direccion->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($entidades->id_entidad);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entidades->id_entidad;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionGetModalEdit($id) {
        $tipo_entidad = TipoEntidad::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        $tipo_documento = TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $entidad = Entidades::find()->where(["fecha_del" => null])->all();
        $data = Entidades::findOne($id);
        $direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $data->id_entidad])->one();

        $plantilla = Yii::$app->controller->renderPartial("editar", [
            "entidad" => $data,
            "tipo_entidad" => $tipo_entidad,
            "tipo_documento" => $tipo_documento,
            "direccion" => $direccion,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }
    public function actionBuscarDocumento()
    {
        $numero_documento = $_POST["numero_documento"];
        $tipo_documento = $_POST["tipo_documento"];
        //     print_r($tipoDocumento)
        $tipodc = null;
        if ($tipo_documento == 1) {

            $tipodc = 'dni';
        } else if ($tipo_documento == 2) {
            $tipodc = 'ruc';
        }

        $docservico = Utils::getConsultaDocumento($tipodc, $numero_documento);
        //   print_r($tipodc);
        // die();
        //   echo $docservico;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $docservico;


    }
    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id 
                $entidades = Entidades::findOne($post['id_entidad']);
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];

                $entidades->id_usuario_act = Yii::$app->user->getId();
                $entidades->fecha_act = Utils::getFechaActual();
                $entidades->ipmaq_act = Utils::obtenerIP();

                if (!$entidades->update()) {
                    Utils::show($entidades->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Persona");
                }

                $direccion = Direcciones::findOne($post['id_direccion']);
                $direccion->id_direccion = $post['id_direccion'];
                $direccion->id_entidad = $entidades->id_entidad;
                $direccion->id_ubigeo = $post['ubigeos'];
                $direccion->direccion = $post['direccion'];
                $direccion->urbanizacion = $post['urbanizacion'];
                $direccion->referencias = $post['referencias'];
                $direccion->flg_estado = Utils::ACTIVO;
                $direccion->id_usuario_act = Yii::$app->user->getId();
                $direccion->fecha_act = Utils::getFechaActual();
                $direccion->ipmaq_act = Utils::obtenerIP();

                if (!$direccion->update()) {
                    Utils::show($direccion->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos direccoin");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entidades->id_entidad;


            // echo json_encode($entidades->id_entidad);
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
                $entidades = Entidades::findOne($post['id_entidad']);
                $entidades->id_usuario_del = Yii::$app->user->getId();
                $entidades->fecha_del = Utils::getFechaActual();
                $entidades->ipmaq_del = Utils::obtenerIP();

                if (!$entidades->save()) {
                    Utils::show($entidades->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro entidades");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            //    echo json_encode($entidades->id_entidad);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $entidades->id_entidad;
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
            $command = Yii::$app->db->createCommand('call listadoEntidades(:row,:length,:buscar)');
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
                "documento" => $row['documento'],
                "numero_documento" => $row['numero_documento'],
                "razon_social" => $row['razon_social'],
                "telefono" => $row['telefono'],
                "correo" => $row['correo'],
                "accion" => '<button class="btn btn-sm btn-light-success font-weight-bold mr-2" onclick="funcionEditar(' . $row["id_entidad"] . ')"><i class="flaticon-edit"></i>Editar</button>
                             <button class="btn btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminar(' . $row["id_entidad"] . ')"><i class="flaticon-delete"></i>Eliminar</button>',
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

    public function actionExportarEntidades(){
        $data = Consultas::exportarEntidades();


        $filename = "ENTIDADES.xlsx";

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
        $sheet->setCellValue('C2', 'TOTAL ENTIDADES');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(Url::to('@app/modules/manifiestoventa/assets/images/logo.jpeg'));
        $drawing->setCoordinates('A1');

        $sheet->getStyle('A6:J6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:J6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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

        $sheet->setCellValue('A6', 'item');
        $sheet->setCellValue('B6', 'id_entidad');
        $sheet->setCellValue('C6', 'descripcion');
        $sheet->setCellValue('D6', 'documento');
        $sheet->setCellValue('E6', 'numero_documento');
        $sheet->setCellValue('F6', 'razon_social');
        $sheet->setCellValue('G6', 'telefono');
        $sheet->setCellValue('H6', 'correo');
        $sheet->setCellValue('J6', 'total');

        $i = 7;
        foreach ($data as $k => $v) {

            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['id_entidad']);
            $sheet->setCellValue('C' . $i, $v['descripcion']);
            $sheet->setCellValue('D' . $i, $v['documento']);
            $sheet->setCellValue('E' . $i, $v['numero_documento']);
            $sheet->setCellValue('F' . $i, $v['razon_social']);
            $sheet->setCellValue('G' . $i, $v['telefono']);
            $sheet->setCellValue('H' . $i, $v['correo']);
            $sheet->setCellValue('J' . $i, $v['total']);

            $i++;
        }

        $sheet->getStyle('A6' . ':J' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'J') as $columnID) {
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
