<?php

namespace app\modules\facturas\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use Exception;
use app\components\Utils;
 

/**
 * Default controller for the `seguridad` module
 */
class BoletasController extends Controller {

    public $enableCsrfValidation = false;
 
    public function actionLista() {
        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        try {
            $command = Yii::$app->db->createCommand('call listadoBoletas(:row,:length,:buscar)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            
              $botones = '<a  class="btn btn-icon btn-light-primary mr-2" target="_blank" href="facturas/default/imprimir-factura/' . $row["id_ventas_factura"] . '"><i class="flaticon-eye"></i></a>
                              <a  class="btn btn-icon btn-light-danger mr-2" target="_blank" onclick="NotaCredito(' . $row["id_ventas_factura"] . ')"><i class="flaticon-delete"></i></a>
                             ';

            if ($row["estado_codigo"] == '666' || $row["estado_codigo"] == null) {
                $botones .= ' <a class="btn btn-icon btn-light-info btn-sm mr-2"  onclick="funcionFacturacion(' . $row["id_ventas_factura"] . ')"><i class="flaticon-coins"></i></a>';
            }
            
            
               $data[] = [
                "serie" => $row['serie'],
                "correlativo" => $row['correlativo'],
                "guia_venta" => $row['guia_venta'],
                "fecha_reg" => $row['fecha_reg'],
                "total_monto" => $row['total_monto'],
                "cliente" => $row['cliente'],
                "numero_documento" => $row['numero_documento'],
                "tipo_comprobante" => $row['tipo_comprobante'],
                "estado" => $row['estado'],
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

}
