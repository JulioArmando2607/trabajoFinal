<?php

namespace app\modules\reportekilometraje\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `reportekilometraje` module
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

    public function actionLista() {

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $fechaInicio = empty($_POST["query"]["fechaInicio"]) ? '' : $_POST["query"]["fechaInicio"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $result = null;


        try {

            $command = Yii::$app->db->createCommand('call reporteKilometraje(@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" target="_blank" href="liquidacion/default/imprimirv/' . $row["id_vehiculo"] . '"><i class="icon-2x flaticon-doc"></i></a>';

            $data[] = [
                "id_vehiculo" => $row['id_vehiculo'],
                "vehiculo" => $row['vehiculo'],
               // "fecha_emision" => date("d/m/Y", strtotime($row['fecha_emision'])),
                "suma_total_kl" => $row['suma_total_kl'],
                "alertaKilometraje" => $row['alertaKilometraje'],
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
}
