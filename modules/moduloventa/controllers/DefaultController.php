<?php

namespace app\modules\moduloventa\controllers;

use app\models\ProductosInventario;
use yii\web\Controller;
use Yii;
use app\components\Utils;
use app\models\Caja;
use app\models\DetalleVentas;
use app\models\Productos;
use app\models\Ventas;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * Default controller for the `moduloventa` module
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

    public function actionGetModal()
    {
        $productos = ProductosInventario::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearVenta", [
            "productos" => $productos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionListCliente()
    {
        $command = Yii::$app->db->createCommand('call clientes()');
        $cliente = $command->queryAll();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $cliente;
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {

                if (!empty($post["venta"])) {

                    $total = 0.00;
                    foreach ($post["venta"] as $v) {
                        $total = $total + $v["total"];
                    }

                    $venta = new Ventas();
                    $venta->id_cliente = $post["cliente"];
                    $venta->numero = Utils::generarCodigoVenta();
                    $venta->monto = $total;
                    $venta->fecha_venta = Utils::getFechaActual();
                    $venta->estado = Utils::ACTIVO;
                    $venta->id_usuario_reg = Yii::$app->user->getId();
                    $venta->fecha_reg = Utils::getFechaActual();
                    $venta->ipmaq_reg = Utils::obtenerIP();

                    if (!$venta->save()) {
                        Utils::show($venta->getErrors(), true);
                        throw new HttpException("No se puede guardar datos venta");
                    }

                    foreach ($post["venta"] as $v) {
                        $detalle = new DetalleVentas();
                        $detalle->id_venta = $venta->id_venta;
                        $detalle->id_producto = $v["id_producto"];
                        $detalle->cantidad = $v["cantidad"];
                        $detalle->monto = $v["total"];
                        $detalle->id_usuario_reg = Yii::$app->user->getId();
                        $detalle->fecha_reg = Utils::getFechaActual();
                        $detalle->ipmaq_reg = Utils::obtenerIP();

                        if (!$detalle->save()) {
                            Utils::show($detalle->getErrors(), true);
                            throw new HttpException("No se puede guardar datos detalle venta");
                        }
                    }

                    $transaction->commit();
                }


            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            echo json_encode($venta->id_venta);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }
    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                $venta = Ventas::findOne($post['id_venta']);
                $venta->estado = Utils::INACTIVO;
                $venta->id_usuario_act = Yii::$app->user->getId();
                $venta->fecha_act = Utils::getFechaActual();
                $venta->ipmaq_act = Utils::obtenerIP();

                if (!$venta->save()) {
                    Utils::show($venta->getErrors(), true);
                    throw new HttpException("No se puede cancelar venta");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            echo json_encode($venta->id_venta);
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

        $total_registro = 0;

        try {
            $command = Yii::$app->db->createCommand('call listadoVenta(:row,:length,:buscar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];
        foreach ($result as $k => $row) {

            $botones = "";
            if ($row['estado'] == 1) {
                $botones = '<a href="venta/default/imprimir/' . $row['id_venta'] . '" target="_blank" class="btn  btn-sm btn-light-success font-weight-bold mr-2" ><i class="flaticon-file-2"></i>Boleta</a>
                             <button class="btn  btn-sm btn-light-danger font-weight-bold mr-2" onclick="funcionEliminarVenta(' . $row["id_venta"] . ')"><i class="flaticon-close"></i>Cancelar</button>';
            }

            $data[] = [
                "numero" => $row['numero'],
                "cliente" => $row['cliente'],
                "monto" => $row['monto'],
                "estado" => ($row['estado'] == 1 ? 'PROCESADO' : 'CANCELADO'),
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
