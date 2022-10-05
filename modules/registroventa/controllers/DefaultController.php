<?php

namespace app\modules\registroventa\controllers;

use app\components\Utils;
use app\models\Agente;
use app\models\Entidades;
use app\models\Estados;
use app\models\RegistroVenta;
use app\models\Ubigeos;

use app\models\Via;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;

/**
 * Default controller for the `registroventa` module
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

    public function actionCrear() {
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $estado=Estados::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
            "rem_des_client" => $rem_des_client,
            "agente" => $agente,
            "estado"=>$estado,
            "ubigeos"=>$ubigeos
        ]);
    }
    public function actionEditar($id) {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaRegistroVenta(:idRegistroVenta)');
            $command->bindValue(':idRegistroVenta', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $estado=Estados::find()->where(["fecha_del" => null])->all();
        $ubigeos = Ubigeos::find()->where(["fecha_del" => null])->all();

        return $this->render('editar', [
            "registroventa"=>$result,
            "rem_des_client" => $rem_des_client,
            "agente" => $agente,
            "estado"=>$estado,
            "ubigeos"=>$ubigeos
        ]);
    }
    public function actionDelete() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id
                $rv = RegistroVenta::findOne($post['id']);
                $rv->id_usuario_del = Yii::$app->user->getId();
                $rv->fecha_del = Utils::getFechaActual();
                $rv->ipmaq_del = Utils::obtenerIP();

                if (!$rv->save()) {
                    Utils::show($rv->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Utils::jsonEncode($rv->id_registro_venta);

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $registroventa = new  RegistroVenta();
                $registroventa->fecha_emision = $post["fecha"];
                $registroventa->serie = $post["serie"];
                $registroventa->factura = $post["factura"];
                $registroventa->id_cliente = $post["idCliente"];
                $registroventa->valor_venta = $post["valor_venta"];
                $registroventa->igv = $post["igv"];
                $registroventa->total = $post["total"];
               // $registroventa->fecha_cancelacion = $post["fecha_cancelacion"];
                $registroventa->monto_depositado = $post["monto_depositado"];
                $registroventa->monto_diferencia = $post["monto_diferencia"];
                $registroventa->id_estado =$post["idEstado"];

                $registroventa->id_usuario_reg = Yii::$app->user->getId();
                $registroventa->fecha_reg = Utils::getFechaActual();
                $registroventa->ipmaq_reg = Utils::obtenerIP();

                if (!$registroventa->save()) {
                    Utils::show($registroventa->getErrors(), true);
                    throw new HttpException("No se puede guardar datos");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $registroventa->id_registro_venta;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $registroventa = RegistroVenta::findOne($post["id_registro_venta"]);
                $registroventa->fecha_emision = $post["fecha"];
                $registroventa->serie = $post["serie"];
                $registroventa->factura = $post["factura"];
                $registroventa->id_cliente = $post["idCliente"];
                $registroventa->valor_venta = $post["valor_venta"];
                $registroventa->igv = $post["igv"];
                $registroventa->total = $post["total"];
                $registroventa->fecha_cancelacion = $post["fecha_cancelacion"];
                $registroventa->monto_depositado = $post["monto_depositado"];
                $registroventa->monto_diferencia = $post["monto_diferencia"];
                $registroventa->id_estado = $post["idEstado"];

                $registroventa->id_usuario_reg = Yii::$app->user->getId();
                $registroventa->fecha_reg = Utils::getFechaActual();
                $registroventa->ipmaq_reg = Utils::obtenerIP();

                if (!$registroventa->save()) {
                    Utils::show($registroventa->getErrors(), true);
                    throw new HttpException("No se puede guardar datos");
                }


                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $registroventa->id_registro_venta;
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
        $mes = empty($_POST["query"]["mes"]) ? '' : $_POST["query"]["mes"];
        // $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoRegistroVenta(:row,:length,:buscar,:fechal,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $perpage);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':fechal', $mes);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        $data = [];

        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="registroventa/default/editar/' . $row["id_registro_venta"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["nombre_estado"] == "PENDIENTE") {
               $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminarRV(' . $row["id_registro_venta"] . ')"><i class="flaticon-delete"></i></button>';
            }

            $data[] = [
                "id_registro_venta" => $row['id_registro_venta'],
                "fecha_emision" => $row['fecha_emision'],
                "nComprobante" => $row['nComprobante'],
                "cliente" => $row['cliente'],
                "valor_venta" => $row['valor_venta'],
                "igv" => $row['igv'],
                "total" => $row['total'],
                "fecha_cancelacion" => $row['fecha_cancelacion'],
                "monto_depositado" => $row['monto_depositado'],
                "monto_diferencia" => $row['monto_diferencia'],
                "nombre_estado" => $row['nombre_estado'],


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
