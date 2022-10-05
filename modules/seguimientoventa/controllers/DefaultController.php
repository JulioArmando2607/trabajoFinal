<?php

namespace app\modules\seguimientoventa\controllers;

use yii\web\Controller;
use Yii;
use app\components\Utils;
use app\models\GuiaVenta;
use app\models\GuiaVentaDestino;
use app\models\Estados;
use app\models\EstadosVenta;
use app\models\Archivo;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use yii\filters\AccessControl;

/**
 * Default controller for the `guiaventas` module
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            //  'subir',
                            'update',
                            'uload',
                            'lista',
                            'crear',
                            'editar'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionEditar($id) {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaSeguimientoV(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $estados = Estados::find()->where(["fecha_del" => null])->all();
        $estados_venta = EstadosVenta::find()->where(["fecha_del" => null])->all();
        $guia_cliente = GuiaVentaDestino::find()->where(["fecha_del" => null, "id_guia_remision" => $id])->all();
        $data = GuiaVenta::findOne($id);

        return $this->render('editar', [
                    "seguimiento" => $result,
                    "estados_venta" => $estados_venta,
                    "estados" => $estados,
                    "guia_cliente" => $guia_cliente,
                    "guia_remision" => $data,
        ]);
    }

    public function actionUpdate() {
        $id = $_POST["id_guia_venta"];

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $seguimiento = GuiaVenta::findOne($id);
                $seguimiento->id_estado = $post['estado'];
                $seguimiento->comentario = $post['comentario'];
                $seguimiento->id_estados_venta = $post['id_estado_venta'];
                $seguimiento->factura_boleta = $post['factura_boleta'];
                $seguimiento->fecha_entrega = $post['fecha_entrega'];

                //  $seguimiento->flg_estado = Utils::ACTIVO;
                $seguimiento->id_usuario_act = Yii::$app->user->getId();
                $seguimiento->fecha_act = Utils::getFechaActual();
                $seguimiento->ipmaq_act = Utils::obtenerIP();


                if (!$seguimiento->save()) {
                    Utils::show($seguimiento->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision cliente");
                }
                
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            // echo json_encode($seguimiento->id_guia_venta);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $seguimiento->id_guia_venta;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionUload($id) {
      
        $unico = uniqid();

       
        if (is_array($_FILES) && count($_FILES) > 0) {
            if (($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpeg") ||
                    ($_FILES["file"]["type"] == "image/png") ||
                    ($_FILES["file"]["type"] == "image/gif")
            ) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file(
                                $_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . '/archivos/' . $unico . '.' . $ext
                        )) {
                    //more code here...

                    $ruta = Utils::DB_STORAGE;


                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        $archivos = new \app\models\Archivo();
                        $archivos->nombre_archivo = $unico . '.' . $ext;
                        $archivos->ruta_archivo = $ruta;
                        $archivos->id_guia = $id;
                        $archivos->ip_server = Utils::getUrl();
                        $archivos->flg_estado = Utils::ACTIVO;
                        $archivos->id_usuario_reg = Yii::$app->user->getId();
                        $archivos->fecha_reg = Utils::getFechaActual();
                        $archivos->ipmaq_reg = Utils::obtenerIP();

                        if (!$archivos->save()) {
                            Utils::show($archivos->getErrors(), true);
                            throw new HttpException("No se puede guardar datos guia remision cliente");
                        }

                        $seguimiento = GuiaVenta::findOne($id);
                        $seguimiento->id_archivo = $archivos->id_archivo;
                        $seguimiento->id_usuario_act = Yii::$app->user->getId();
                        $seguimiento->fecha_act = Utils::getFechaActual();
                        $seguimiento->ipmaq_act = Utils::obtenerIP();

                        if (!$seguimiento->save()) {
                            Utils::show($seguimiento->getErrors(), true);
                            throw new HttpException("No se puede guardar datos Persona");
                        }
                    } catch (Exception $exc) {
                        echo $exc->getTraceAsString();
                    }

                    $transaction->commit();
                    //echo json_encode($archivos->id_archivo);
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    Yii::$app->response->data = $archivos->id_archivo;


                   // echo $ruta . $_FILES['file']['name'];
                   // print_r( $_FILES['file']['name']);
                 //   die();
                } else {
                    echo 1;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }

    }

    public function actionLista() {
        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoGuiaVenta(:row,:length,:buscar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = [];
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="seguimientoventa/default/editar/' . $row["id_guia_venta"] . '"><i class="flaticon-edit"></i></a>';

            $data[] = [
                "numero_guia" => $row['numero_guia'],
                "forma_pago" => $row['forma_pago'],
                "fecha" => $row['fecha'],
                "estado_venta" => $row['estado_venta'],
                "nombre_estado" => $row['nombre_estado'],
                "tipo_entrega" => $row['tipo_entrega'],
                "accion" => $botones
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
