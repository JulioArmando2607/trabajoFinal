<?php

namespace app\modules\facturas\controllers;

use app\modules\facturas\query\Consultas;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
 

/**
 * Default controller for the `seguridad` module
 */
class NotascreditoController extends Controller {

    public $enableCsrfValidation = false;
 
 
    public function actionLista() {
        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoNotasCredito(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            
              $botones = '<a  class="btn btn-icon btn-light-primary mr-2" target="_blank" href="facturas/notascredito/imprimir-nota/' . $row["id_notas_credito"] . '"><i class="flaticon-eye"></i></a>';

            if ($row["estado_codigo"] == '666') {
                $botones .= '<a class="btn btn-icon btn-light-info btn-sm mr-2"  onclick="funcionVolverNota(' . $row["id_notas_credito"] . ')"><i class="flaticon-coins"></i></a>';
            }
            
            
               $data[] = [
                "serie" => $row['serie'],
                "correlativo" => $row['correlativo'],
                "documento_electronico_aplicar" => $row['documento_electronico_aplicar'],
                "fecha_emision" => $row['fecha_emision'],
                 
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
    public function actionImprimirNota($id) {


        $result = Consultas::getImprimirNotas($id);
        $tipo_doc_ide = '';
        $tipodc='';
      if ($result["serie"] == '007') {
           /// $tipo_doc_ide = 'F: ';
            $tipodc='F';
        } else if ($result["serie"] == '006') {
          //  $tipo_doc_ide = 'B: ';
            $tipodc='B';
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

        $pdf->Cell(25);
        $pdf->setY(10);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 16);
        $pdf->Cell(5, $textypos, "PEGASO SERVICE EXPRESS S.A.C.");

        $pdf->setY(45);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "CALLE PABLO DE OLAVIDE 365 URB.COLONIAL");
        $pdf->setY(50);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "RUC: 20524917891");
        $pdf->setY(55);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        ///$pdf->Cell(5, 0, $result["tipo_comprobante"] . " Electronica");
        $pdf->Cell(5, 0, "Nota de credito" . " Electronica");
        $pdf->setY(60);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "Documento: " .$tipodc. $result["serie"]. "-". $result["correlativo"]);
        $pdf->setY(65);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "FECHA: " . $result['fecha_emision']); //----------------------------------------------
        $pdf->setY(74);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");
        $pdf->setY(76);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 10);

        $pdf->MultiCell(100, 4, "CLIENTE: " . utf8_decode($result["nombre_razon_cliente"]), '', 'J', 0);
        $pdf->setY(86);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, utf8_decode("N° Doc: "  . $result["doc_cliente"]));
        $pdf->setY(90);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 11);
        $pdf->Cell(5, 0, "-----------------------------------------------------------------------");

        $pdf->setY(95);
        $pdf->setX(2);
        $header = array('CANT', 'DESCR', 'VALOR');
        $w = array(40, 35, 40);
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 0, 0, '');
        }
        $pdf->Ln();

        $pdf->setY(105);
        $pdf->setX(1);
        $pdf->MultiCell($w[0], 4, "1", '', 'J', 0);
        $pdf->setY(105);
        $pdf->setX(17);
        $pdf->MultiCell(55, 4, "" . utf8_decode($result["motivo_n_credito"]." ".$result["documento_electronico_aplicar"]), '', 'J', 0);
        $pdf->setY(105);
        $pdf->setX(76);
        $pdf->MultiCell($w[2], 4, "S/" . $result["total"], '', 'J', 0);
        $pdf->setY(125);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(130);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SUBTOTAL:", '', 'J', 0);
        $pdf->setY(130);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["precio_unitario"], '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "IGV:", '', 'J', 0);
        $pdf->setY(135);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["igv"], '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "TOTAL:", '', 'J', 0);
        $pdf->setY(140);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, "S/" . $result["total"], '', 'J', 0);

        $pdf->setY(145);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "SON:", '', 'J', 0);
        $pdf->setY(145);
        $pdf->setX(26);
        $pdf->MultiCell(0, 4, strtoupper(Utils::convertirLetras($result["total"])), '', 'J', 0);

        $pdf->setY(150);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);

        $pdf->setY(155);
        $pdf->setX(2);
       /// $pdf->MultiCell(0, 4, "Forma de pago: " . utf8_decode($result["forma_pago"]), '', 'J', 0);

        $pdf->setY(160);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "-----------------------------------------------------------------------", '', 'J', 0);


        $pdf->setY(165);
        $pdf->setX(2);
        $pdf->MultiCell(0, 4, "AGENCIA: ".$usuarioconsul["nombre_agencia"], '', 'J', 0);
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

}
