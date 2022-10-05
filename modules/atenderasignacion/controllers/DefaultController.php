<?php

namespace app\modules\atenderasignacion\controllers;

use app\models\AtencionPedidos;
use app\models\PedidoCliente;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\atenderasignacion\query\Consultas;
use app\models\Productos;
use app\models\TipoCarga;
use app\models\GuiaRemision;
use app\models\DetalleGuiaRemitente;
use app\models\GuiaRemisionCliente;
use Faker\Provider\es_ES\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\filters\AccessControl;
use yii\web\HttpException;

/**
 * Default controller for the `guiaremision` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'crear',
                            'buscar-numero-doc-trs',
                            'reg-entidad',
                            'crear-entidad',
                            'get-modal',
                            'create',
                            'get-modal-edit',
                            'update',
                            'delete',
                            'lista',
                            'exportar',
                            'editar',
                            'crear',
                            'buscar-direccion',
                            'detalle-guia',
                            'guia-cliente',
                            'buscar-guia',
                            'reg-agente',
                            'create-agente',
                            'buscar-agente',
                            'buscar-entidad',
                            'buscar-tipo-v',
                            'listar-agente',
                            'listar-entidad',
                            'buscar-numero-doc',
                            'reg-transportista',
                            'crear-transportista',
                            'buscar-transportista',
                            'imprimir',
                            'mail',
                            'subir-imagen'
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCrear()
    {

        $via = Via::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $via_->id_via])->all();
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        return $this->render('crear', [
            "via" => $via,
            "rem_des_client" => $rem_des_client,
            "agente" => $agente,
            "conductor" => $conductor,
            "vehiculo" => $vehiculo,
            "producto" => $producto,
            "tipoCarga" => $tipoCarga,
            "via_tipo" => $via_tipo
        ]);
    }

    public function actionBuscarNumeroDocTrs()
    {

        $numerog = $_POST["numero"];
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call consultaNumeroDocumentoTrs(:numero_guia)');
            $command->bindValue(':numero_guia', $numerog);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionRegEntidad()
    {
        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearE", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrearEntidad()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $entidades = new Entidades();
                $entidades->id_tipo_entidad = $post['tipo_entidad'];
                $entidades->id_tipo_documento = $post['tipo_documento_entidad'];
                $entidades->numero_documento = $post['numero_documento'];
                $entidades->razon_social = $post['razon_social'];
                $entidades->telefono = $post['telefono'];
                $entidades->correo = $post['correo'];
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
            Utils::jsonEncode($entidades->id_entidad);
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionCrearTransportista()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $transportista = new \app\models\Transportista();
                $transportista->id_tipo_documento = $post['tipo_documento'];
                $transportista->numero_documento = $post['numero_documento'];
                $transportista->razon_social = $post['razon_social'];
                $transportista->telefono = $post['telefono'];
                $transportista->correo = $post['correo'];
                $transportista->ubigeo = $post['ubigeos'];
                $transportista->direccion = $post['direccion'];
                $transportista->urbanizacion = $post['urbanizacion'];
                $transportista->referencia = $post['referencias'];
                $transportista->flg_estado = Utils::ACTIVO;
                $transportista->id_usuario_reg = Yii::$app->user->getId();
                $transportista->fecha_reg = Utils::getFechaActual();
                $transportista->ipmaq_reg = Utils::obtenerIP();
                if (!$transportista->save()) {
                    Utils::show($transportista->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Persona");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $transportista->id_transportista;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionRegAgente()
    {
        $plantilla = Yii::$app->controller->renderPartial("crearAgente", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreateAgente()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $agente = new Agente();
                $agente->cuenta = $post['cuenta'];
                $agente->agente = $post['agente'];
                $agente->flg_estado = Utils::ACTIVO;
                $agente->id_usuario_reg = Yii::$app->user->getId();
                $agente->fecha_reg = Utils::getFechaActual();
                $agente->ipmaq_reg = Utils::obtenerIP();
                if (!$agente->save()) {
                    Utils::show($agente->getErrors(), true);
                    throw new HttpException("No se puede guardar datos Agente");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $agente->id_agente;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionListarAgente()
    {

        $direccion = Agente::find()->where(["fecha_del" => null])->all();
        $data = "";
        foreach ($direccion as $a) {
            $data .= '<option value="" disabled selected>Seleccione</option><option value="' . $a->id_agente . '">' . $a->cuenta . ' ' . $a->agente . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarTransportista()
    {

        $id_transportista = $_POST['id_transportista'];
        $direccion = \app\models\Transportista::find()->where(["fecha_del" => null, "id_transportista" => $id_transportista])->all();
        $data = "";
        foreach ($direccion as $a) {
            $data .= '<option value="' . $a->id_transportista . '">' . $a->numero_documento . ' ' . $a->razon_social . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarAgente()
    {

        $id_agente = $_POST['id_agente'];
        $direccion = Agente::find()->where(["fecha_del" => null, "id_agente" => $id_agente])->all();
        $data = "";
        foreach ($direccion as $a) {
            $data .= '<option value="' . $a->id_agente . '">' . $a->cuenta . ' ' . $a->agente . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarGuia()
    {
        $numerog = $_POST["numero"];
        $serie = $_POST["serie"];
        $result = null;
        try {
            $command = Yii::$app->db->createCommand('call consultaNumeroGuia(:numero_guia,:serie_guia)');
            $command->bindValue(':numero_guia', $numerog);
            $command->bindValue(':serie_guia', $serie);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
    }

    public function actionBuscarDireccion()
    {
        $id_entidad = $_POST["id_entidad"];
        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call BuscarDireccion(:entidad)');
            $command->bindValue(':entidad', $id_entidad);
            $result = $command->queryAll();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = "";
        foreach ($result as $d) {
            $data .= '<option value="' . $d["id_direccion"] . '">' . $d["direccion"] . ' ' . $d["urbanizacion"] . '-' . $d["nombre_distrito"] . ' ' . $d["nombre_provincia"] . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarTipoV()
    {
        $id_via = $_POST["id_via"];
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $id_via])->all();
        $data = "";
        foreach ($via_tipo as $d) {
            $data .= '<option value="' . $d["id_tipo_via_carga"] . '">' . $d->tipo_via_carga . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionListarEntidad()
    {
        $entidades = Entidades::find()->where(["fecha_del" => null])->all();
        $data = "";
        foreach ($entidades as $d) {
            $data .= ' <option value="" disabled selected>Seleccione</option><option value="' . $d->id_entidad . '">' . $d->razon_social . ' ' . $d->numero_documento . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarEntidad()
    {

        $id_entidad = $_POST['id_entidad'];
        $entidades = Entidades::find()->where(["fecha_del" => null, "id_entidad" => $id_entidad])->all();
        $data = "";
        foreach ($entidades as $d) {
            $data .= '<option value="' . $d->id_entidad . '">' . $d->razon_social . ' ' . $d->numero_documento . '</option>';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarNumeroDoc()
    {
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

    public function actionRegTransportista()
    {

        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearTransportista", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = new GuiaRemision();

                //$guiaRemision->serie = $post["serie"];
                //$guiaRemision->numero_guia = $post["numero"];
                $guiaRemision->fecha = $post["fecha"];
                $guiaRemision->fecha_traslado = $post["traslado"];
                $guiaRemision->id_via = $post["via"];
                $guiaRemision->id_tipo_via = $post["via_tipo"];
                $guiaRemision->id_cliente = $post["id_cliente"];
                $guiaRemision->id_agente = $post["agente"];
                $guiaRemision->id_remitente = $post["remitente"];
                $guiaRemision->id_direccion_partida = $post["direccion_partida"];
                $guiaRemision->id_destinatario = $post["destinatario"];
                $guiaRemision->id_direccion_llegada = $post["direccion_llegada"];
                $guiaRemision->id_conductor = $post["conductor"];
                $guiaRemision->id_vehiculo = $post["vehiculo"];
                $guiaRemision->nm_solicitud = $post["nm_solicitud"];
                $guiaRemision->id_archivo_inicio =  $post["idArchv"];
                $guiaRemision->id_pedido_cliente=$post["id_pedido_cliente"];
                $guiaRemision->id_estado = Utils::PENDIENTE_GUIA;
                $guiaRemision->id_usuario_reg = Yii::$app->user->getId();
                $guiaRemision->fecha_reg = Utils::getFechaActual();
                $guiaRemision->ipmaq_reg = Utils::obtenerIP();

                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }

                $detalle_guia = empty($post["detalle_guia"]) ? [] : $post["detalle_guia"];

                foreach ($detalle_guia as $dg) {
                    $detalleGuia = new DetalleGuiaRemitente();
                    $detalleGuia->id_guia_remision = $guiaRemision->id_guia_remision;
                    $detalleGuia->id_producto = $dg["id_producto"];
                    $detalleGuia->cantidad = $dg["cantidad"];
                    $detalleGuia->peso = $dg["peso"];
                    $detalleGuia->volumen = $dg["volumen"];
                    $detalleGuia->alto = $dg["alto"];
                    $detalleGuia->ancho = $dg["ancho"];
                    $detalleGuia->largo = $dg["largo"];
                    $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
                    $detalleGuia->fecha_reg = Utils::getFechaActual();
                    $detalleGuia->ipmaq_reg = Utils::obtenerIP();
                    if (!$detalleGuia->save()) {
                        Utils::show($detalleGuia->getErrors(), true);
                        throw new HttpException("No se puede guardar datos detalle guia");
                    }
                }
                $detalle_guia_rc = empty($post["detalle_guia_rc"]) ? [] : $post["detalle_guia_rc"];
                foreach ($detalle_guia_rc as $dgr) {
                    $guia_remision_cliente = new GuiaRemisionCliente();
                    $guia_remision_cliente->id_guia_remision = $guiaRemision->id_guia_remision;
                    $guia_remision_cliente->grs = $dgr["grs"];
                    $guia_remision_cliente->gr = $dgr["gr"];
                    $guia_remision_cliente->ft = $dgr["ft"];
                    $guia_remision_cliente->oc = $dgr["oc"];
                    $guia_remision_cliente->id_tipo_carga = $dgr["id_tipo_carga"];
                    $guia_remision_cliente->cantidad = $dgr["cantidad"];
                    $guia_remision_cliente->peso = $dgr["peso"];
                    $guia_remision_cliente->volumen = $dgr["volumen"];
                    $guia_remision_cliente->alto = $dgr["alto"];
                    $guia_remision_cliente->ancho = $dgr["ancho"];
                    $guia_remision_cliente->largo = $dgr["largo"];
                    $guia_remision_cliente->descripcion = $dgr["descripcion"];
                    $guia_remision_cliente->id_estado_mercaderia = Utils::RECOGIDO;
                    $guia_remision_cliente->id_usuario_reg = Yii::$app->user->getId();
                    $guia_remision_cliente->fecha_reg = Utils::getFechaActual();
                    $guia_remision_cliente->ipmaq_reg = Utils::obtenerIP();
                    if (!$guia_remision_cliente->save()) {
                        Utils::show($guia_remision_cliente->getErrors(), true);
                        throw new HttpException("No se puede guardar datos guia remision cliente");
                    }
                }

                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditar($id)
    {

        $result = [];
        try {
            $command = Yii::$app->db->createCommand('call consultaatepedidos(:id_atencion_pedidos)');
            $command->bindValue(':id_atencion_pedidos', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        $guia = GuiaRemision::findOne($id);
        $via = Via::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();
        $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $via_->id_via])->all();
        $rem_des_client = Entidades::find()->where(["fecha_del" => null, "id_tipo_entidad" => Utils::TIPO_ENTIDAD_CLIENTE])->all();
        $conductor = Consultas::getConductor();
        $vehiculo = Consultas::getVehiculo();
        $agente = Agente::find()->where(["fecha_del" => null])->all();
        $producto = Productos::find()->where(["fecha_del" => null])->all();
        $tipoCarga = TipoCarga::find()->where(["fecha_del" => null])->all();
        return $this->render('editar', [
            "guia" => $guia,
            "via" => $via,
            "rem_des_client" => $rem_des_client,
            "agente" => $agente,
            "conductor" => $conductor,
            "vehiculo" => $vehiculo,
            "producto" => $producto,
            "tipoCarga" => $tipoCarga,
            "consultaatepedidos" => $result,
            "via_tipo" => $via_tipo,
            "transportista" => $transportista
        ]);
    }

    public function actionDetalleGuia()
    {
        $id = $_POST["id_guia"];
        $detalle_guia = Consultas::getDetalleGuia($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public function actionGuiaCliente()
    {
        $id = $_POST["id_guia"];
        $guia_cliente = Consultas::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }

    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = GuiaRemision::findOne($post["id_guia"]);
                $guiaRemision->id_usuario_del = Yii::$app->user->getId();
                $guiaRemision->fecha_del = Utils::getFechaActual();
                $guiaRemision->ipmaq_del = Utils::obtenerIP();
                if (!$guiaRemision->save()) {
                    Utils::show($guiaRemision->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $transaction->rollback();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
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
        $usuario = Yii::$app->user->getId();
        $row = ($page * $perpage) - $perpage;
        $length = ($perpage * $page) - 1;
        $total_registro = 0;
        try {
            $command = Yii::$app->db->createCommand('call listadoateAuxPedidos(:row,:length,:buscar,:auxiliar,@total)');
            $command->bindValue(':row', $row);
            $command->bindValue(':length', $length);
            $command->bindValue(':buscar', $buscar);
            $command->bindValue(':auxiliar', $usuario);
            $result = $command->queryAll();
            $total_registro = Yii::$app->db->createCommand("select @total as result;")->queryScalar();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }
        $data = [];
        $nm_solicitud = "";

        foreach ($result as $k => $row) {
            $botones = '';
            $nm_solicitud = strval($row["nm_solicitud"]);

            if ($row["nombre_estado"] === "ASIGNADO") {
                $botones .= '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="atenderasignacion/default/editar/' . $row["id_atencion_pedidos"] . '"><i class="flaticon-edit"></i></a>'
                    . ' <button class="btn btn-icon btn-light-danger btn-sm mr-2" onclick="funcionEnviarCorreo(' . $row["id_atencion_pedidos"] . ',' . "'$nm_solicitud'" . ')"><i class="fa fa-envelope"></i></button>';


            }
            $botones .= '<a class="btn btn-icon btn-light-success btn-sm mr-2"  target="_blank"  href="atenderasignacion/default/imprimir/' . $row["nm_solicitud"] . '"><i class="flaticon2-print"></i></a>';


            $data[] = [
                "fecha" => $row['fecha'],
                "nm_solicitud" => $row['nm_solicitud'],
                "hora_recojo" => $row['hora_recojo'],
                "razon_social" => $row['razon_social'],
                "contacto" => $row['contacto'],
                "telefono" => $row['telefono'],
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

    public function actionExportar()
    {


        $data = Consultas::getImprimirExcel();
        $filename = "TotalGuias.xlsx";
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
        $sheet->setCellValue('C2', 'TOTAL GUIAS');
        $sheet->getStyle('C2')->applyFromArray(['font' => ['bold' => true, 'size' => 20], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]]);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/SistemaPegaso/modules/manifiestoventa/assets/images/logo.jpeg');
        $drawing->setCoordinates('A1');
        $sheet->getStyle('A6:Z6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('335593');
        $sheet->getStyle('A6:Z6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getPageSetup()->setScale(73);
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(false);
        $sheet->getPageMargins()->setTop(0);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setLeft(0);
        $sheet->getPageMargins()->setBottom(0);
        $sheet->setCellValue('A6', 'ITEM');
        $sheet->setCellValue('B6', 'CLIENTE FINAL');
        $sheet->setCellValue('C6', 'FECHA RECOJO');
        $sheet->setCellValue('D6', 'CLIENTE DESTINO');
        $sheet->setCellValue('E6', 'GUIA CLIENTE');
        $sheet->setCellValue('F6', 'FACTURA');
        $sheet->setCellValue('G6', 'GUIA PEGASO');
        $sheet->setCellValue('H6', 'PROVINCIA');
        $sheet->setCellValue('I6', 'BULTOS');
        $sheet->setCellValue('J6', 'PESO');
        $sheet->setCellValue('K6', 'PESO VOLUMEN');
        $sheet->setCellValue('L6', 'DESCRIPCION');
        $sheet->setCellValue('M6', 'VIA');
        $sheet->setCellValue('N6', 'DATALOGER');
        $sheet->setCellValue('O6', 'EMPRESA TRANSPORTE');
        $sheet->setCellValue('P6', 'FACTURA');
        $sheet->setCellValue('Q6', 'GUIA TRANSPORTISTA');
        $sheet->setCellValue('R6', 'STATUS DE ENTREGA');
        $sheet->setCellValue('S6', 'RECIBIDO');
        $sheet->setCellValue('T6', 'FECHA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('U6', 'HORA DE ENTREGA REAL DE LA CARGA');
        $sheet->setCellValue('V6', 'FECHA DE ENTREGA REAL DE LA DOCUMENTACION AL CLIENTE');
        $sheet->setCellValue('W6', 'OBSERVACION EN EL CASO NO CUMPLA CON LOS REQUISITOS');
        $sheet->setCellValue('X6', 'QUIEN RELIZO LA ENTREGA');
        $sheet->setCellValue('Y6', 'USUARIO REG');
        $sheet->setCellValue('Z6', 'FECHA REG');
        $i = 7;
        foreach ($data as $k => $v) {
            $fecha = ($v['fecha'] == null) ? '-' : date("d/m/Y", strtotime($v['fecha']));
            $sheet->setCellValue('A' . $i, $k + 1);
            $sheet->setCellValue('B' . $i, $v['remitente']);
            $sheet->setCellValue('C' . $i, $v['fecha_traslado']);
            $sheet->setCellValue('D' . $i, $v['destinatario']);
            $sheet->setCellValue('E' . $i, $v['guia_cliente']);
            $sheet->setCellValue('F' . $i, $v['ft']);
            $sheet->setCellValue('G' . $i, $v['numero_guia']);
            $sheet->setCellValue('H' . $i, $v['destino']);
            $sheet->setCellValue('I' . $i, $v['cantidad']);
            $sheet->setCellValue('J' . $i, $v['peso']);
            $sheet->setCellValue('K' . $i, $v['volumen']);
            $sheet->setCellValue('L' . $i, $v['unidad_medida']);
            $sheet->setCellValue('M' . $i, $v['via']);
            $sheet->setCellValue('N' . $i, $v['datalogger']);
            $sheet->setCellValue('O' . $i, $v['transportista']);
            $sheet->setCellValue('P' . $i, $v['factura_transportista']);
            $sheet->setCellValue('Q' . $i, $v['guia_remision_transportista']);
            $sheet->setCellValue('R' . $i, $v['estado_mercaderia']);
            $sheet->setCellValue('S' . $i, $v['recibido_por']);
            $sheet->setCellValue('T' . $i, $v['fecha_hora_entrega']);
            $sheet->setCellValue('U' . $i, $v['hora_entrega']);
            $sheet->setCellValue('V' . $i, $v['fecha_cargo']);
            $sheet->setCellValue('W' . $i, $v['observacion']);
            $sheet->setCellValue('X' . $i, $v['realizo_entrega']);
            $sheet->setCellValue('Y' . $i, $v['usuario']);
            $sheet->setCellValue('Z' . $i, $v['fecha_reg']);
            $i++;
        }

        $sheet->getStyle('A6' . ':Z' . $i)->applyFromArray($styleBorder);

        foreach (range('A', 'Z') as $columnID) {
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


    public function actionMail()
    {
        $id_atencion_pedidos = $_POST['id_atencion_pedidos'];
        $numero_solicitud = $_POST['nm_solicitud'];
        $numero_guia = "";
        $correo_cliente = null;
        $nombre_cliente = "Cliente";
        $origen = "";
        $destino = "";
        $pedido = "";
        $final = false;
        $datos_correo = Consultas::getDatosCorreo($numero_solicitud);
        $contar = count($datos_correo);
        foreach ($datos_correo as $r) {
            $guiarem = $r["numero_guia"];

        }
        if ($contar >= 1 && $guiarem != null) {
            $tabla = "";

            foreach ($datos_correo as $r) {
                $correo_cliente = $r["correo"];
                $nombre_cliente = $r["cliente"];
                $origen = $r["origen"];
                $destino = $r["destino"];
                $pedido = $numero_solicitud;
                $lista = "";
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


            }


            $mensaje = '<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
img { -ms-interpolation-mode: bicubic; }
img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
table { border-collapse: collapse !important; }
body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
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
div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus dolor aliquid omnis consequatur est deserunt, odio neque blanditiis aspernatur, mollitia ipsa distinctio, culpa fuga obcaecati!
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
            <tr>
                <td align="center" valign="top" style="font-size:0; padding: 20px;" bgcolor="#0836D6">
                <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                        <tr>
                            <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                <img src="https://pegasoserviceexpress.com/logo_pegaso.png" width="250" style="display: block; border: 0px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bolder; line-height: 24px; padding-top: 25px;">                                  
                            <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                <img data-imagetype="External" src="https://s3.amazonaws.com/linio-live-transactional/REVAMP/general/icons/icon_tick_green.png" alt="Icono" width="20" height="20">
                                 ¡Tu pedido ha sido confirmado!
                            </h2>
                            <br>
                            <p>PEDIDO : ' . $pedido . '</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                Hola <b>' . $nombre_cliente . ',</b>
                                Te informamos que hemos generado las siguientes guias para su pedido.
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
                <td align="center" height="100%" valign="top" width="100%" style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
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
                                      <a href="https://pegasoserviceexpress.com/ClientePegaso/web/" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #66b3b7; padding: 15px 30px; border: 1px solid #66b3b7; display: block;">Seguimiento de carga</a>
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
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                    <tr>
                        <td bgcolor="#ffffff" align="center" style="padding: 30px 30px 30px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                            <p style="margin: 0;">Este correo electrónico fue creado y probado con PEGASO SERVICE EXPRESS. <a href="https://pegasoserviceexpress.com/" style="color: #5db3ec;">Quienes Somos PEGASO SERVICE EXPRESS</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

            try {
                $correo_clientes = explode(";", $correo_cliente);
               
                Yii::$app->mailer->compose()
                    ->setFrom('seguimiento@pegasoserviceexpress.com')
                    ///->setTo($rf)
                    ->setSubject('Pedido N°' . $numero_solicitud)
                    ->setHtmlBody($mensaje);
                  foreach ($correo_clientes as $receiver) {

                        $mail->setTo($receiver)
                                ->send();
                    };
                    //->attach($path)
                   // ->send();
                $final = true;
                $atencionpedidos = AtencionPedidos::findOne($id_atencion_pedidos);
                $atencionpedidos->flg_estado = 28;
                $atencionpedidos->id_usuario_act = Yii::$app->user->getId();
                $atencionpedidos->fecha_act = Utils::getFechaActual();
                $atencionpedidos->ipmaq_act = Utils::obtenerIP();

                if (!$atencionpedidos->save()) {
                    Utils::show($atencionpedidos->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }
                $pedidocliente = PedidoCliente::findOne($atencionpedidos->id_pedido_cliente);
                $pedidocliente->id_estado = 28;
                $pedidocliente->id_usuario_act = Yii::$app->user->getId();
                $pedidocliente->fecha_act = Utils::getFechaActual();
                $pedidocliente->ipmaq_act = Utils::obtenerIP();

                if (!$pedidocliente->save()) {
                    Utils::show($pedidocliente->getErrors(), true);
                    throw new HttpException("No se puede guardar datos guia remision");
                }
            } catch (Exception $ex) {
                Utils::show($ex, true);
                $final = false;
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $final;
        } else {
            echo 0;
        }

    }

    public function actionImprimir($id)
    {
        $result = Consultas::getDatosOrdenServicioC($id);
        $idUser = Yii::$app->user->getId();
        $actual = date("Y-m-d H:i:s");
        $user = \app\models\Usuarios::findOne($idUser);
        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->Image(Url::to('@app/modules/manifiestoventa/assets/images/logopegaso.png'), 10, 1, 80);
        // $pdf->AddFont('Dotmatrx', '', 'Dotmatrx.php');
        $pdf->SetFont('ARIAL', 'B', 15);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->Ln(20);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', '', 17);
        $pdf->MultiCell(80, 4, "PEDIDO: " . $result['nm_solicitud']);
        $pdf->Ln(5);
        $pdf->setX(2);
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->MultiCell(70, 4, utf8_decode("" . $result['cliente']));
        $pdf->Ln(10);

        $result2 = Consultas::getDatosOrdenServicioS($id);
        foreach ($result2 as $gTT) {
            $pdf->setX(2);
            $pdf->SetFont('ARIAL', 'B', 10);
            $pdf->Cell(0, -16, '______________________________________________');
            $pdf->setX(2);
            $pdf->SetFont('ARIAL', '', 9);
            $pdf->Cell(35, -8, utf8_decode("Via: " . $gTT['nombre_via']));
            $pdf->setX(2);
            $pdf->SetFont('ARIAL', '', 9);
            $pdf->Cell(35, -2, utf8_decode("Origen: " . $gTT['origen']));
            $pdf->Ln(5);
            $pdf->setX(2);
            $pdf->Cell(35, -2, utf8_decode("Destino: " . $gTT['destino']));
            $pdf->Ln(5);
            $pdf->setX(2);
            $pdf->Cell(35, -2, "Total Bulto: " . $gTT['cantidad']);
            $pdf->Ln(6);
            $guiaCliente = \app\modules\guiaremision\query\Consultas::getGuiaCliente($gTT['id_guia_remision']);
            foreach ($guiaCliente as $gc) {
                $pdf->setX(2);
                $pdf->Cell(1, -3, "Guia Cliente: " . $gc['grs'] . "-" . $gc['gr']);
                $pdf->Ln(5);
            }
            $pdf->Ln(2);

        }
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->setX(2);
        $pdf->Cell(0, -1, 'Fecha: ' . $actual, 0, 0, '');
        $pdf->setX(2);
        $pdf->Cell(5, 10, "Usuario: " . $user->usuario);
        $pdf->setX(2);
        $pdf->Cell(0, 50, '______________________________');
        $pdf->setX(27);
        $pdf->Cell(0, 60, 'Firma', 0, 0, '');

        $pdf->Output();

    }

    function actionSubirImagen()
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
                     ///   $archivos->id_guia = $id;
                        $archivos->ip_server = Utils::getUrl();
                        $archivos->flg_estado = Utils::ACTIVO;
                        $archivos->id_usuario_reg = Yii::$app->user->getId();
                        $archivos->fecha_reg = Utils::getFechaActual();
                        $archivos->ipmaq_reg = Utils::obtenerIP();

                        if (!$archivos->save()) {
                            Utils::show($archivos->getErrors(), true);
                            throw new HttpException("No se puede guardar datos guia remision cliente");
                        }
                    } catch (Exception $exc) {
                        echo $exc->getTraceAsString();
                    }

                    $transaction->commit();
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    Yii::$app->response->data = $archivos->id_archivo;


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
}
