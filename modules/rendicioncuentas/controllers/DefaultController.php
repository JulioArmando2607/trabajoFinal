<?php

namespace app\modules\rendicioncuentas\controllers;

use app\components\Utils;

use app\models\DetalleRendicionCuentas;
use app\models\RendicionCuentas;
use app\models\Usuarios;
use app\modules\rendicioncuentas\query\Consultas;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\HttpException;
use Yii;

/**
 * Default controller for the `rendicioncuentas` module
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
    public function actionCrear($id) {
        $usabono=Usuarios::find()->where(["fecha_del" => null])->all();
        $personas = Consultas::getPersonas();
        return $this->render('crear', [
                "usabono"=>$usabono,
                "personas"=>$personas
        ]);
    }
    public function actionEditar($id) {
        $rd = RendicionCuentas::findOne($id);
        $usabono=Usuarios::find()->where(["fecha_del" => null, "id_usuario" => $rd->id_abono_cuenta_de])->all();
        $us=Usuarios::find()->where(["fecha_del" => null])->all();
        $personas = Consultas::getPersonas();

        return $this->render('editar', [
            "rd"=>$rd,
            "usabono"=>$usabono,
             "us"=>$us,
            "personas"=>$personas

        ]);
    }

    public function actionUpdate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $rendicionCuentas = RendicionCuentas::findOne($post["id_rendicion_cuentas"]);
                $rendicionCuentas->fecha = $post["fecha"];
                $rendicionCuentas->nr_operacion = $post["nr_operacion"];
                $rendicionCuentas->id_abono_cuenta_de = $post["abono_cuenta_de"];
                $rendicionCuentas->rinde = $post["rinde"];
                $rendicionCuentas->importe_entregado = $post["importe_entregado"];
                $rendicionCuentas->diferencia_depositar_reembolsar = $post["diferencia_depo"];
                $rendicionCuentas->id_usuario_act = Yii::$app->user->getId();
                $rendicionCuentas->fecha_act = Utils::getFechaActual();
                $rendicionCuentas->ipmaq_act = Utils::obtenerIP();

                if (!$rendicionCuentas->save()) {
                    Utils::show($rendicionCuentas->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $rendicionCuentas->id_rendicion_cuentas;
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
                $rendicionCuentas = RendicionCuentas::findOne($post['id']);
                $rendicionCuentas->id_usuario_del = Yii::$app->user->getId();
                $rendicionCuentas->fecha_del = Utils::getFechaActual();
                $rendicionCuentas->ipmaq_del = Utils::obtenerIP();

                if (!$rendicionCuentas->save()) {
                    Utils::show($rendicionCuentas->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro via");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Utils::jsonEncode($rendicionCuentas->id_rendicion_cuentas);

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCreate(){   if (Yii::$app->request->post()) {
        $transaction = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post();
        try {
            $rendicionCuentas =new  RendicionCuentas();
            $rendicionCuentas->fecha = $post["fecha"];
            $rendicionCuentas->nr_operacion = $post["nr_operacion"];
            $rendicionCuentas->id_abono_cuenta_de = $post["abono_cuenta_de"];
            $rendicionCuentas->rinde = $post["rinde"];
            $rendicionCuentas->importe_entregado = $post["importe_entregado"];
            $rendicionCuentas->diferencia_depositar_reembolsar = $post["diferencia_depo"];
            $rendicionCuentas->id_usuario_reg = Yii::$app->user->getId();
            $rendicionCuentas->fecha_reg = Utils::getFechaActual();
            $rendicionCuentas->ipmaq_reg = Utils::obtenerIP();

            if (!$rendicionCuentas->save()) {
                Utils::show($rendicionCuentas->getErrors(), true);
                throw new HttpException("No se puede guardar datos guia remision");
            }

            $detalle_rc = empty($post["detalle_guia_rc"]) ? [] : $post["detalle_guia_rc"];

            foreach ($detalle_rc as $dg) {

                $detalleGuia = new DetalleRendicionCuentas();
                $detalleGuia->id_rendicion_cuentas = $rendicionCuentas->id_rendicion_cuentas;
                $detalleGuia->fecha = $dg["fecha_d_rc"];
                $detalleGuia->proveedor = $dg["proveedor_d_rc"];
                $detalleGuia->nm_documento = $dg["ndocumento_d_rc"];
                $detalleGuia->concepto = $dg["concepto_d_rc"];
                $detalleGuia->monto = $dg["monto_d_rc"];
                $detalleGuia->flg_estado=1;
                $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
                $detalleGuia->fecha_reg = Utils::getFechaActual();
                $detalleGuia->ipmaq_reg = Utils::obtenerIP();

                if (!$detalleGuia->save()) {
                    Utils::show($detalleGuia->getErrors(), true);
                    throw new HttpException("No se puede guardar datos detalle guia");
                }

                }
            $transaction->commit();
        } catch (Exception $ex) {
            Utils::show($ex, true);
            $transaction->rollback();
        }

        // echo json_encode($guiaRemision->id_guia_remision);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $rendicionCuentas->id_rendicion_cuentas;
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
        // $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoRendicionCuentas(:row,:length,:buscar,@total)');
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
        //    $botones =  '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="rendicioncuentas/default/crear/' . $row["id_rendicion_cuentas"] . '"><i class="flaticon-edit"></i></a>';
            $botones =  '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="rendicioncuentas/default/editar/' . $row["id_rendicion_cuentas"] . '"><i class="flaticon-edit"></i></a>';

            if ($row["flg_estado"] == 1) {
                $botones .= '<button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEliminar(' . $row["id_rendicion_cuentas"] . ')"><i class="flaticon-delete"></i></button>';
            }

            $data[] = [
                "fecha" => $row['fecha'],
                "nr_operacion" => $row['nr_operacion'],
                "abono_cuenta_de" => $row['abono_cuenta_de'],
                "rinde" => $row['rinde'],
                "importe_entregado" => $row['importe_entregado'],
                "diferencia_depositar_reembolsar" => $row['diferencia_depositar_reembolsar'],
                "flg_estado" => $row['flg_estado'],
                "total_gasto" => $row['total_gasto'],

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

    public function actionDetalleCuentas() {
        $id = $_POST["id_rendicion_cuentas"];
        $detalle_guia=DetalleRendicionCuentas::find()->where(["fecha_del" => null, "id_rendicion_cuentas" => $id])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public function actionGetModalEdit($id) {
        $data = DetalleRendicionCuentas::findOne($id);
        $plantilla = Yii::$app->controller->renderPartial("editarDetalleR", [
            "data" => $data
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdateDetalle() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id
                $via = DetalleRendicionCuentas::findOne($post['id_detalle_rendicion_cuentas']);
                $via->fecha = $post['fecha'];
                $via->proveedor = $post['proveedor'];
                $via->nm_documento = $post['nm_documento'];
                $via->monto = $post['monto'];
                $via->concepto = $post['concepto'];
                $via->id_usuario_act = Yii::$app->user->getId();
                $via->fecha_act = Utils::getFechaActual();
                $via->ipmaq_act = Utils::obtenerIP();

                if (!$via->save()) {
                    Utils::show($via->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Via");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($via->id_detalle_rendicion_cuentas);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionRegDetalle() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {


                $drc = new DetalleRendicionCuentas();
                $drc->id_rendicion_cuentas = $post['id_rendicion_cuentas'];
                $drc->fecha = $post['fecha_rc'];
                $drc->proveedor = $post['proveedor_rc'];
                $drc->nm_documento = $post['nm_documento_rc'];
                $drc->monto = $post['monto_rc'];
                $drc->concepto = $post["monto_rc"];
                $drc->id_usuario_reg= Yii::$app->user->getId();
                $drc->fecha_reg= Utils::getFechaActual();
                $drc->ipmaq_reg = Utils::obtenerIP();

                if (!$drc->save()) {
                    Utils::show($drc->getErrors(), true);
                    throw new HttpException("No se puede actualizar datos Via");
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Utils::jsonEncode($drc->id_detalle_rendicion_cuentas);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionDeleteDetalle() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            try {
                //Traemos los datos mediante el id
                $rendicionCuentas = DetalleRendicionCuentas::findOne($post['id']);
                $rendicionCuentas->id_usuario_del = Yii::$app->user->getId();
                $rendicionCuentas->fecha_del = Utils::getFechaActual();
                $rendicionCuentas->ipmaq_del = Utils::obtenerIP();
                if (!$rendicionCuentas->save()) {
                    Utils::show($rendicionCuentas->getErrors(), true);
                    throw new HttpException("No se puede eliminar registro via");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Utils::jsonEncode($rendicionCuentas->id_detalle_rendicion_cuentas);

        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }


}
