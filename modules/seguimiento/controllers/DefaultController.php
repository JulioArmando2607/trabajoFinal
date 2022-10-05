<?php

namespace app\modules\seguimiento\controllers;

use app\models\Personas;
use app\models\User;
use app\models\Usuarios;
use app\modules\cerrarasignacion\query\Consultas;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\models\Estados;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use app\models\GuiaRemisionCliente;
use app\modules\seguimiento\query\ConsultasG;
use Exception;
use yii\web\HttpException;
use yii\filters\AccessControl;

/**
 * Default controller for the `guiaremision` module
 */
class DefaultController extends Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'get-modal-edit-g-c',
                            'updateg',
                            'update',
                            'lista',
                            'guia-cliente',
                            'uload',
                            'uloadrc',
                            //'subir',
                            'editar',
                            'cargar-imagen',
                            'mail',
                            'estado-guia',
                            'solicitar-permiso'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEditar($id)
    {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaSeguimiento(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }


        $estados = Estados::find()->where(["fecha_del" => null])->all();
        $guia_cliente = GuiaRemisionCliente::find()->where(["fecha_del" => null, "id_guia_remision" => $id])->all();
        $data = GuiaRemision::findOne($id);


        return $this->render('editar', [
            "seguimiento" => $result,
            "estados" => $estados,
            "guia_cliente" => $guia_cliente,
            "guia_remision" => $data,
            //  "response" => $response,
        ]);
    }

    public function actionGetModalEditGC($id)
    {


        $estados = Estados::find()->where(["fecha_del" => null])->all();
        $tipo_estados = Estados::find()->where(["fecha_del" => null])->all();
        $data = GuiaRemisionCliente::findOne($id);
        $tipo_carga = TipoCarga::find()->where(["fecha_del" => null, "id_tipo_carga" => $data->id_tipo_carga])->one();
        $archivo = \app\models\Archivo::findAll($data);
        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaSeguimientorc(:id_guia)');
            $command->bindValue(':id_guia', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $plantilla = Yii::$app->controller->renderPartial("editarguiacliente", [
            "guiaRC" => $data,
            "tipo_estados" => $tipo_estados,
            "tipo_carga" => $tipo_carga,
            "estados" => $estados,
            "archivo" => $archivo,
            "seguimientorc" => $result,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionUpdateg()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();

            $chekss = json_decode($_POST["chekAplicarTodos"], TRUE);
            $fr = $chekss;
            if (count($fr) >= 1) {

                $id_guia = $post["id_guia_remision"];
                $lista_guia = GuiaRemisionCliente::find()->where(["id_guia_remision" => $id_guia])->all();

                $guiaRC = null;


                try {     //Traemos los datos mediante el id
                    foreach ($lista_guia as $g) {
                        $guiaRC = GuiaRemisionCliente::findOne($g->id_guia_remision_cliente);

                        $guiaRC->id_estado_cargo = $post['estado_cargo'];
                        $guiaRC->recibido_por = $post['recibido_por'];
                        $guiaRC->entregado_por = $post['entregado_por'];
                        $guiaRC->id_estado_mercaderia = $post['estado_mercaderia'];
                        $guiaRC->observacion = $post['obsevacion'];
                        $guiaRC->fecha_hora_entrega = $post['fecha_hora_entrega'];
                        $guiaRC->fecha_cargo = $post['fecha_cargo'];
                        $guiaRC->hora_entrega = $post['hora_entrega'];

                        $guiaRC->id_usuario_act = Yii::$app->user->getId();
                        $guiaRC->fecha_act = Utils::getFechaActual();
                        $guiaRC->ipmaq_act = Utils::obtenerIP();

                        if (!$guiaRC->save()) {
                            Utils::show($guiaRC->getErrors(), true);
                            throw new HttpException("No se puede actualizar datos");
                        }
                    }

                    $transaction->commit();
                } catch (Exception $ex) {
                    Utils::show($ex, true);
                    $transaction->rollback();
                }


                $ar = null;
//                try {
//                    $fecha = '';
//                    $fecha = $post['fecha_hora_entrega'];
//                    $aplicartodos = ConsultasG::getActualizarGC($post['recibido_por'], $post['entregado_por'], $post['estado_mercaderia'], $post['obsevacion'], $post['fecha_hora_entrega'], $post['fecha_cargo'], $post['hora_entrega'],
//                        Yii::$app->user->getId(), Utils::getFechaActual(), Utils::obtenerIP(), $post['id_guia_remision']);
//                    $transaction->commit();
//                } catch
//                (Exception $ex) {
//                    Utils::show($ex, true);
//                    $transaction->rollback();
//                }


                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = $guiaRC->id_guia_remision_cliente;
            } else if (count($chekss) == 0) {


                try {     //Traemos los datos mediante el id
                    $guiaRC = GuiaRemisionCliente::findOne($post['id_guia_remision_cliente']);

                    $guiaRC->id_estado_cargo = $post['estado_cargo'];
                    $guiaRC->recibido_por = $post['recibido_por'];
                    $guiaRC->entregado_por = $post['entregado_por'];
                    $guiaRC->id_estado_mercaderia = $post['estado_mercaderia'];
                    $guiaRC->observacion = $post['obsevacion'];
                    $guiaRC->fecha_hora_entrega = $post['fecha_hora_entrega'];
                    $guiaRC->fecha_cargo = $post['fecha_cargo'];
                    $guiaRC->hora_entrega = $post['hora_entrega'];


                    $guiaRC->id_usuario_act = Yii::$app->user->getId();
                    $guiaRC->fecha_act = Utils::getFechaActual();
                    $guiaRC->ipmaq_act = Utils::obtenerIP();

                    if (!$guiaRC->save()) {
                        Utils::show($guiaRC->getErrors(), true);
                        throw new HttpException("No se puede actualizar datos");
                    }

                    $transaction->commit();
                } catch (Exception $ex) {
                    Utils::show($ex, true);
                    $transaction->rollback();
                }

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = $guiaRC->id_guia_remision_cliente;
            }
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public
    function actionUpdate()
    {
        $id = $_POST["id_guia_remision"];


        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();

            $post = Yii::$app->request->post();

            try {     //Traemos los datos mediante el id
                $seguimiento = GuiaRemision::findOne($id);
                $seguimiento->id_estado = $post['estado'];

                $seguimiento->comentario = $post['comentario'];
                //  $seguimiento->flg_estado = Utils::ACTIVO;
                $seguimiento->id_usuario_act = Yii::$app->user->getId();
                $seguimiento->fecha_act = Utils::getFechaActual();
                $seguimiento->ipmaq_act = Utils::obtenerIP();
               if($post['estado']==4){
                   $seguimiento->flg_guia = 1;
               }
                if (!$seguimiento->save()) {
                    Utils::show($seguimiento->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }

                if ($seguimiento->id_estado == 4) {
                    $this->mail($seguimiento->id_guia_remision);
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }


            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $seguimiento->id_guia_remision;

            //echo json_encode($seguimiento->id_guia_remision);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista()
    {

        $usuario = Usuarios::findOne(Yii::$app->user->getId());

        $page = empty($_POST["pagination"]["page"]) ? 0 : $_POST["pagination"]["page"];
        $pages = empty($_POST["pagination"]["pages"]) ? 1 : $_POST["pagination"]["pages"];
        $buscar = empty($_POST["query"]["generalSearch"]) ? '' : $_POST["query"]["generalSearch"];
        $perpage = $_POST["pagination"]["perpage"];
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;

        $total_registro = 0;
        try {

            $command = Yii::$app->db->createCommand('call listadoGuiaRemision(:row,:length,:buscar,@total)');
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

            //  $botones = '';
            $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="seguimiento/default/editar/' . $row["id_guia_remision"] . '"><i class="flaticon2-edit"></i></a>';

            if ($row["flg_guia"] == 1) {
                $botones = '<a class="btn btn-icon btn-light-primary btn-sm mr-2" onclick="funcionSoicitarPermiso(' . $row["id_guia_remision"] . ')"><i class="flaticon-lock"></i></a>';
            }

            if ($usuario->id_perfil == 9) {
                //$botones = '';
                $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="seguimiento/default/editar/' . $row["id_guia_remision"] . '"><i class="flaticon2-edit"></i></a>';
            }

            $data[] = [
                "numero_guia" => $row['numero_guia'],
                "fecha" => $row['fecha'],
                "fecha_traslado" => $row['fecha_traslado'],
                "origen" => $row['origen'],
                "destino" => $row['destino'],
                "nombre_estado" => $row['nombre_estado'],
                "remitente" => $row['remitente'],
                "destinatario" => $row['destinatario'],
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

    public
    function actionGuiaCliente()
    {
        $id = $_POST["id_guia_remision"];
        $guia_cliente = ConsultasG::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }

    public
    function actionCargarImagen()
    {


//        print_r($_POST["idGuia"]);
//        die();

        $id_archivo = 0;
        $unico = uniqid();

        if (isset($_POST["idArchivo"]) && isset($_POST["idGuia"])) {
            if ($_POST['idArchivo'] == null) {
                $id_archivo = 0;
            } else {
                $id_archivo = intval($_POST['idArchivo']);
            }
            $id = $_POST['idGuia'];
        }

        if (is_array($_FILES) && count($_FILES) > 0) {


            if (($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpeg") ||
                ($_FILES["file"]["type"] == "image/png") ||
                ($_FILES["file"]["type"] == "image/gif")
            ) {


                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

                if (move_uploaded_file(
                    $_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . '/archivos/' . $unico . '.' . $ext
                )) {


                    $ruta = Utils::DB_STORAGE;

                    $transaction = Yii::$app->db->beginTransaction();


                    try {
                        $archivos = new \app\models\Archivo();
                        if ($archivos->id_archivo = $id_archivo) {
                            $archivos = \app\models\Archivo::findOne($id_archivo);
                            $archivos->nombre_archivo = $unico . '.' . $ext;
                            $archivos->ruta_archivo = $ruta;
                            //  $archivos->id_guia = $id;
                            $archivos->ip_server = Utils::getUrl();
                            $archivos->flg_estado = Utils::ACTIVO;
                            $archivos->id_usuario_act = Yii::$app->user->getId();
                            $archivos->fecha_act = Utils::getFechaActual();
                            $archivos->ipmaq_act = Utils::obtenerIP();
                            if (!$archivos->update()) {
                                Utils::show($archivos->getErrors(), true);
                                throw new HttpException("No se puede guardar datos guia remision cliente");
                            }
                        } else {

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
                        }
                        $seguimiento = GuiaRemision::findOne($id);
                        $seguimiento->id_archivo = $archivos->id_archivo;
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
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    Yii::$app->response->data = $archivos->id_archivo;
                    // echo $ruta . $_FILES['file']['name'];
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

    public
    function actionUloadrc($id)
    {
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

                        $seguimiento = GuiaRemisionCliente::findOne($id);
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

                    echo $ruta . $_FILES['file']['name'];
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

    public static function actionEstadoGuia()
    {
        $id = $_POST['pedido'];
        $nm_solicitud = '';
        $correo = '';
        $pedido = '';

        $numero_guia = "";
        $correo_cliente = null;
        $nombre_cliente = "Cliente";
        $origen = "";
        $destino = "";
        $tabla = "";
        $lista = "";

        $datosEnvioC = ConsultasG::getEnvioCorreoSegGuia($id);

        $contar = count($datosEnvioC);

        foreach ($datosEnvioC as $t) {
            $pedido = $t["nm_solicitud"];
            //   $datos_correo_total = Consultas::getDatosCorreo($r["numero_solicitud"]);
            $validarGAte = ConsultasG::getContarGuiasEs($t["nm_solicitud"]);
            if ($validarGAte["estadopedido"] == 'Pendiente') {

            } else {
                $datos_correo_total = Consultas::getDatosCorreo($t["nm_solicitud"]);
                foreach ($datos_correo_total as $r) {
                    $correo_cliente = $r["correo"];
                    $nombre_cliente = $r["cliente"];
                    $origen = $r["origen"];
                    $destino = $r["destino"];

                    $result = Consultas::getListaGuia($r["numero_guia"]);
                    foreach ($result as $s) {
                        $lista .= '<tr>
                            <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                        Guia Cliente
                            </td>
                            <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                     ' . $s["guia_cliente"] . '
                            </td>
                       </tr>';
                    }
                    $tabla .= '  <tr>
                        <td align="left" style="padding-top: 20px;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                        Guia Pegaso Service Express
                                    </td>
                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                       ' . $r["numero_guia"] . '
                                    </td>
                                </tr>
                                 ' . $lista . '
                                 <tr>
                                 
                                  <tr>
                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                    <b>  Total de Bultos </b>
                                    </td>
                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                   <b>   ' . $r["peso"] . '</b>
                                    </td>
                                    
                                </tr>
                           
                        
                            
                       </tr>
                             
                            </table>
                           
                        </td>
                    </tr>
                     <td align="center" valign="top" style="font-size:0;"> 
                            <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                            <p style="font-weight: 800;">Origen</p>
                                            <p>' . $origen . '</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                            <p style="font-weight: 800;">Destino</p>
                                            <p>' . $destino . '</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>';

                    $mensaje = '<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
    <div
        style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
         
    </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" valign="top" style="font-size:0; padding: 20px;" bgcolor="#0836D6">
                            <div
                                style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                            class="mobile-center">
                                            <img src="https://pegasoserviceexpress.com/logo_pegaso.png" width="250"
                                                style="display: block; border: 0px;" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;"
                            bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bolder; line-height: 24px; padding-top: 25px;">
                                        <h2
                                            style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                            <img data-imagetype="External"
                                                src="https://s3.amazonaws.com/linio-live-transactional/REVAMP/general/icons/icon_tick_green.png"
                                                alt="Icono" width="20" height="20">
                                            ¡Estado de Pedido!
                                        </h2>
                                        <br>
                                        <p>PEDIDO : ' . $pedido . ' </p>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p
                                            style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                            Hola <b> ' . $nombre_cliente . ',</b>
                                            las guias asignadas a su pedido ' . $pedido . ' estan entregadas, para mayor detalle en nuestro modulo seguimiento carga.
                                            Gracias por confiar en nosotros, mejorando para brindarle el mejor servicio.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                               ' . $tabla . '
                     

        </tr>
        <tr>

        </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td align="center" height="100%" valign="top" width="100%"
            style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                <tr>

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style=" padding: 20px; background-color: #06099b;" bgcolor="#1b9ba3">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 25px 0 15px 0;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7">
                                    <a href="https://pegasoserviceexpress.com/ClientePegaso/web/" target="_blank"
                                        style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #66b3b7; padding: 15px 30px; border: 1px solid #66b3b7; display: block;">Seguimiento
                                        de carga</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#ffffff" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center"
                            style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;">
                            <p style="margin: 0;">Este correo electrónico fue creado y probado con PEGASO SERVICE
                                EXPRESS. <a href="https://pegasoserviceexpress.com/" style="color: #5db3ec;">Quienes
                                    Somos PEGASO SERVICE EXPRESS</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>';

                    try {


                        $correo_clientes = explode(";", $correo);
                        $mail = Yii::$app->mailer->compose()
                            ->setFrom('seguimiento@pegasoserviceexpress.com')
                            //->setTo($correo)
                            ->setSubject('Pedido N°' . $pedido)
                            ->setCc('seguimiento_zonasur@pegasoserviceexpress.com')
                            //->setTo('armandojulio82@gmail.com')
                            ->setHtmlBody($mensaje);


                        foreach ($correo_clientes as $receiver) {

                            $mail->setTo($receiver)
                                ->send();
                        };


                        $final = true;
                    } catch (Exception $ex) {
                        Utils::show($ex, true);
                        $final = false;
                    }

                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    Yii::$app->response->data = $final;
//        } else {
//            throw new HttpException(404, 'The requested Item could not be found.');
//        }
                }
            }
        }
        // if ($validarGAte["estadopedido"] == 'Pendiente') {
        // } else {
    }

    //  }

    public static function Mail($id)
    {

        $correo = "";
        $final = false;


        $pedido = "";
        $guiapegaso = "";
        $nombre_cliente = "";
        $estado = "";

        //$datos_correo = \app\modules\guiaremision\GuiaRemision::findOne($id);;
        $datos_correo = \app\modules\seguimiento\query\ConsultasG::getEnvioCorreoSegGuia($id);

        $contar = count($datos_correo);

        foreach ($datos_correo as $r) {

            $nombre_cliente = $r["cliente"];
            $pedido = $r["nm_solicitud"];

            $correo = $r["correo"];
            $guiapegaso = $r["numero_guia"];
            $estado = $r["nombre_estado"];
        }
        if ($contar >= 1) {
            $tabla = "";


            $mensaje = '<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
    <div
        style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
         
    </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" valign="top" style="font-size:0; padding: 20px;" bgcolor="#0836D6">
                            <div
                                style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                            class="mobile-center">
                                            <img src="https://pegasoserviceexpress.com/logo_pegaso.png" width="250"
                                                style="display: block; border: 0px;" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;"
                            bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bolder; line-height: 24px; padding-top: 25px;">
                                        <h2
                                            style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                            <img data-imagetype="External"
                                                src="https://s3.amazonaws.com/linio-live-transactional/REVAMP/general/icons/icon_tick_green.png"
                                                alt="Icono" width="20" height="20">
                                            ¡Estado de Pedido!
                                        </h2>
                                        <br>
                                        <p>PEDIDO : ' . $pedido . ' </p>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p
                                            style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                            Hola <b> ' . $nombre_cliente . ',</b>
                                            enviamos el estado de su pedido' . $pedido . ' con guia pegaso ' . $guiapegaso . '. ' . $estado . '
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">

                                            <tr>


                                                 

                                            </tr>
                                            <tr>

                                            <tr>
                                                 

                                            </tr>

                                            <tr>

                                                 
                                            </tr>
                                            
                                              <tr>
  
                                                 
                                            </tr>
                                            
                                            <tr>

                                                 
                                            </tr>


                                </tr>

                            </table>

                        </td>
                    </tr>
                     

        </tr>
        <tr>

        </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td align="center" height="100%" valign="top" width="100%"
            style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                <tr>

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style=" padding: 20px; background-color: #06099b;" bgcolor="#1b9ba3">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 25px 0 15px 0;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7">
                                    <a href="https://pegasoserviceexpress.com/ClientePegaso/web/" target="_blank"
                                        style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #66b3b7; padding: 15px 30px; border: 1px solid #66b3b7; display: block;">Seguimiento
                                        de carga</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#ffffff" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center"
                            style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;">
                            <p style="margin: 0;">Este correo electrónico fue creado y probado con PEGASO SERVICE
                                EXPRESS. <a href="https://pegasoserviceexpress.com/" style="color: #5db3ec;">Quienes
                                    Somos PEGASO SERVICE EXPRESS</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>';

            try {


                $correo_clientes = explode(";", $correo);
                $mail = Yii::$app->mailer->compose()
                    ->setFrom('seguimiento@pegasoserviceexpress.com')
                    //->setTo($correo)
                    ->setSubject('Pedido N°' . $pedido)
                    ->setCc('seguimiento_zonasur@pegasoserviceexpress.com')
                    //        ->setTo('armandojulio82@gmail.com')
                    ->setHtmlBody($mensaje);
                // ->send();

                foreach ($correo_clientes as $receiver) {

                    $mail->setTo($receiver)
                        ->send();
                };


                $final = true;
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $final = false;
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $final;
//        } else {
//            throw new HttpException(404, 'The requested Item could not be found.');
//        }
        } else {
            echo 0;
        }
    }


    public function actionSolicitarPermiso()
    {

        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            $usuario = User::findOne(Yii::$app->user->getId());
            $nombresPersona = Personas::find()->where(["fecha_del" => null, "id_persona" => $usuario->id_persona])->one();
            $nombres = $nombresPersona->nombres . " " . $nombresPersona->apellido_paterno . " " . $nombresPersona->apellido_materno;

            $guiaRemision = GuiaRemision::find()->where(["fecha_del" => null, "id_guia_remision" => $post["id_guia"]])->one();
            try {

                setlocale(LC_TIME, "spanish");

                $final = false;

                $mensaje = "  <br><br>El usuario " . $nombres . " <br><br>Solicita el cambio des estado de la guia " . $guiaRemision->numero_guia . "<br> <br><br>
             <a href='http://147.182.244.87/pegaso/web/externo/api/status/" . $post["id_guia"] . "?code=" . Yii::$app->security->generatePasswordHash($post["id_guia"]) . "'>
            <button type='button' class='btn btn-primary mr-2'>Cambiar estado</button>
            </a>          
            
               <br>
             Atentamente, <br>  <br> <br>  " .
                    '<html><body> <img src="http://147.182.244.87/pegaso/web/assets/3091f183/media/logos/pegasologo.png" width="300" height="100" class="max-h-30px" alt="" /> </body>  </html> ';

                try {
                    $correo = 'armandojulio82@gmail.com;armando_J07@hotmail.com;administracion@pegasoserviceexpress.com';
                    $correo_clientes = explode(";", $correo);
                    $mail = Yii::$app->mailer->compose()
                        ->setFrom('seguimiento@pegasoserviceexpress.com')
                        ->setSubject('CAMBIO DE ESTADO DE GUIA ')
                        ->setHtmlBody($mensaje);


                    foreach ($correo_clientes as $receiver) {
                        $mail->setTo($receiver)
                            ->send();
                    };


                    $final = true;
                } catch (Exception $ex) {
                    Utils::show($ex, true);
                    $final = false;
                }

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->data = $final;

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $post["id_guia"];
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

}
