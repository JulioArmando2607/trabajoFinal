<?php

namespace app\modules\solicitudatencionaux\controllers;

use yii\web\Controller;
use Yii;
use app\models\Via;
use app\components\Utils;
use app\models\Entidades;
use app\models\Direcciones;
use app\models\Agente;
use app\modules\solicitudatencionaux\query\Consultas;
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

/**
 * Default controller for the `guiaremision` module
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
                            'get-modal',
                            'create',
                            'get-modal-edit',
                            'update',
                            'delete',
                            'lista',
                            'crear',
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
                            'reg-entidad',
                            'crear-entidad',
                            'buscar-entidad',
                            'buscar-tipo-v',
                            'listar-agente',
                            'listar-entidad',
                            'buscar-numero-doc',
                            'reg-transportista',
                            'buscar-numero-doc-trs',
                            'crear-transportista',
                            'buscar-transportista'
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

    public function actionCrear() {

        $via = Via::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();

        //$direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $data->id_entidad])->one();
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

    public function actionBuscarNumeroDocTrs() {

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

    public function actionRegEntidad() {

        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();

        $plantilla = Yii::$app->controller->renderPartial("crearE", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos,
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCrearEntidad() {
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

    public function actionCrearTransportista() {
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

    public function actionRegAgente() {
        $plantilla = Yii::$app->controller->renderPartial("crearAgente", []);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreateAgente() {
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

    public function actionListarAgente() {

        $direccion = Agente::find()->where(["fecha_del" => null])->all();

        $data = "";
        foreach ($direccion as $a) {
            $data .= '<option value="" disabled selected>Seleccione</option><option value="' . $a->id_agente . '">' . $a->cuenta . ' ' . $a->agente . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarTransportista() {

        $id_transportista = $_POST['id_transportista'];
        $direccion = \app\models\Transportista::find()->where(["fecha_del" => null, "id_transportista" => $id_transportista])->all();

        $data = "";
        foreach ($direccion as $a) {

            $data .= '<option value="' . $a->id_transportista . '">' . $a->numero_documento . ' ' . $a->razon_social . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarAgente() {

        $id_agente = $_POST['id_agente'];
        $direccion = Agente::find()->where(["fecha_del" => null, "id_agente" => $id_agente])->all();

        $data = "";
        foreach ($direccion as $a) {
            $data .= '<option value="' . $a->id_agente . '">' . $a->cuenta . ' ' . $a->agente . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarGuia() {
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

    public function actionBuscarDireccion() {
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

    public function actionBuscarTipoV() {
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

    public function actionListarEntidad() {

        $entidades = Entidades::find()->where(["fecha_del" => null])->all();

        $data = "";
        foreach ($entidades as $d) {

            $data .= ' <option value="" disabled selected>Seleccione</option><option value="' . $d->id_entidad . '">' . $d->razon_social . ' ' . $d->numero_documento . '</option>';
        }


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
    }

    public function actionBuscarEntidad() {

        $id_entidad = $_POST['id_entidad'];
        $entidades = Entidades::find()->where(["fecha_del" => null, "id_entidad" => $id_entidad])->all();

        $data = "";
        foreach ($entidades as $d) {

            $data .= '<option value="' . $d->id_entidad . '">' . $d->razon_social . ' ' . $d->numero_documento . '</option>';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
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

    public function actionRegTransportista() {

        $tipo_documento = \app\models\TipoDocumentos::find()->where(["fecha_del" => null])->all();
        $ubigeos = \app\models\Ubigeos::find()->where(["fecha_del" => null])->all();
        $plantilla = Yii::$app->controller->renderPartial("crearTransportista", [
            "tipo_documento" => $tipo_documento,
            "ubigeos" => $ubigeos
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ["plantilla" => $plantilla];
    }

    public function actionCreate() {
        if (Yii::$app->request->post()) {
            $transaction = Yii::$app->db->beginTransaction();
            $post = Yii::$app->request->post();
            try {
                $guiaRemision = new GuiaRemision();

                $guiaRemision->serie = $post["serie"];
                $guiaRemision->numero_guia = $post["numero"];
                $guiaRemision->fecha = $post["fecha"];
                $guiaRemision->fecha_traslado = $post["traslado"];
                $guiaRemision->id_via = $post["via"];
                $guiaRemision->id_tipo_via = $post["via_tipo"];
                $guiaRemision->id_cliente = $post["remitente"];
                $guiaRemision->id_agente = $post["agente"];
                $guiaRemision->id_remitente = $post["remitente"];
                $guiaRemision->id_direccion_partida = $post["direccion_partida"];
                $guiaRemision->id_destinatario = $post["destinatario"];
                $guiaRemision->id_direccion_llegada = $post["direccion_llegada"];
                $guiaRemision->id_conductor = $post["conductor"];
                $guiaRemision->id_vehiculo = $post["vehiculo"];

                $guiaRemision->transportista = $post["transportista"];
                $guiaRemision->guia_remision_transportista = $post["guia_remision"];
                $guiaRemision->factura_transportista = $post["factura"];
                $guiaRemision->importe_transportista = $post["importe"];
                $guiaRemision->comentario_transportista = $post["comentario"];
                $guiaRemision->nm_solicitud = $post["nm_solicitud"];
                $guiaRemision->id_estado = Utils::RECOGIDO;
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

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionEditar($id) {

        $result = [];
        try {

            $command = Yii::$app->db->createCommand('call consultaatepedidos(:id_atencion_pedidos)');
            $command->bindValue(':id_atencion_pedidos', $id);
            $result = $command->queryOne();
        } catch (\Exception $e) {
            echo "Error al ejecutar procedimiento" . $e;
        }

        //   $via_tipo = \app\models\TipoViaCarga::find()->where(["fecha_del" => null, "id_via" => $guia->id_via])->all();
        $transportista = \app\models\Transportista::find()->where(["fecha_del" => null])->all();
        $guia = GuiaRemision::findOne($id);
        $via = Via::find()->where(["fecha_del" => null])->all();
        $via_ = Via::find()->where(["fecha_del" => null])->one();

        //$direccion = Direcciones::find()->where(["fecha_del" => null, "id_entidad" => $data->id_entidad])->one();
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

    public function actionDetalleGuia() {
        $id = $_POST["id_guia"];
        $detalle_guia = Consultas::getDetalleGuia($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $detalle_guia;
    }

    public function actionGuiaCliente() {
        $id = $_POST["id_guia"];
        $guia_cliente = Consultas::getGuiaCliente($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = $guia_cliente;
    }

    /* public function actionUpdate() {
      if (Yii::$app->request->post()) {
      $transaction = Yii::$app->db->beginTransaction();
      $post = Yii::$app->request->post();
      try {
      $guiaRemision = GuiaRemision::findOne($post["id_guia"]);
      $guiaRemision->serie = $post["serie"];
      $guiaRemision->numero_guia = $post["numero"];
      $guiaRemision->fecha = $post["fecha"];
      $guiaRemision->fecha_traslado = $post["traslado"];
      $guiaRemision->id_via = $post["via"];
      $guiaRemision->id_cliente = $post["cliente"];
      $guiaRemision->id_agente = $post["agente"];
      $guiaRemision->id_remitente = $post["remitente"];
      $guiaRemision->id_direccion_partida = $post["direccion_partida"];
      $guiaRemision->id_destinatario = $post["destinatario"];
      $guiaRemision->id_direccion_llegada = $post["direccion_llegada"];
      $guiaRemision->id_conductor = $post["conductor"];
      $guiaRemision->id_vehiculo = $post["vehiculo"];
      $guiaRemision->transportista = $post["transportista"];
      $guiaRemision->guia_remision_transportista = $post["guia_remision"];
      $guiaRemision->factura_transportista = $post["factura"];
      $guiaRemision->importe_transportista = $post["importe"];
      $guiaRemision->comentario_transportista = $post["comentario"];
      //                $guiaRemision->id_estado = Utils::PENDIENTE;
      $guiaRemision->id_usuario_act = Yii::$app->user->getId();
      $guiaRemision->fecha_act = Utils::getFechaActual();
      $guiaRemision->ipmaq_act = Utils::obtenerIP();

      if (!$guiaRemision->save()) {
      Utils::show($guiaRemision->getErrors(), true);
      throw new HttpException("No se puede guardar datos guia remision");
      }

      $detalle_guia = empty($post["detalle_guia_edit"]) ? [] : $post["detalle_guia_edit"];

      foreach ($detalle_guia as $dg) {
      if ($dg["flg"] == 1) {
      $detalleGuia = new DetalleGuiaRemitente();
      $detalleGuia->id_guia_remision = $guiaRemision->id_guia_remision;
      $detalleGuia->id_producto = $dg["id_producto"];
      $detalleGuia->cantidad = $dg["cantidad"];
      $detalleGuia->peso = $dg["peso"];
      $detalleGuia->volumen = $dg["volumen"];
      $detalleGuia->id_usuario_reg = Yii::$app->user->getId();
      $detalleGuia->fecha_reg = Utils::getFechaActual();
      $detalleGuia->ipmaq_reg = Utils::obtenerIP();

      if (!$detalleGuia->save()) {
      Utils::show($detalleGuia->getErrors(), true);
      throw new HttpException("No se puede guardar datos detalle guia");
      }
      }
      }

      $detalle_guia_rc = empty($post["detalle_guia_edit_rc"]) ? [] : $post["detalle_guia_edit_rc"];

      foreach ($detalle_guia_rc as $dgr) {
      if ($dgr["flg"] == 1) {
      $guia_remision_cliente = new GuiaRemisionCliente();
      $guia_remision_cliente->id_guia_remision = $guiaRemision->id_guia_remision;
      $guia_remision_cliente->gr = $dgr["gr"];
      $guia_remision_cliente->ft = $dgr["ft"];
      $guia_remision_cliente->oc = $dgr["oc"];
      $guia_remision_cliente->id_tipo_carga = $dgr["id_tipo_carga"];
      $guia_remision_cliente->descripcion = $dgr["descripcion"];
      $guia_remision_cliente->id_usuario_reg = Yii::$app->user->getId();
      $guia_remision_cliente->fecha_reg = Utils::getFechaActual();
      $guia_remision_cliente->ipmaq_reg = Utils::obtenerIP();

      if (!$guia_remision_cliente->save()) {
      Utils::show($guia_remision_cliente->getErrors(), true);
      throw new HttpException("No se puede guardar datos guia remision cliente");
      }
      }
      }

      $detalle_guia_delete = empty($post["detalle_guia_delete"]) ? [] : $post["detalle_guia_delete"];

      foreach ($detalle_guia_delete as $dg) {
      if ($dg["flg"] == 0) {
      $detalleGuia = DetalleGuiaRemitente::findOne($dg["identificadorDetalle"]);
      $detalleGuia->id_usuario_del = Yii::$app->user->getId();
      $detalleGuia->fecha_del = Utils::getFechaActual();
      $detalleGuia->ipmaq_del = Utils::obtenerIP();

      if (!$detalleGuia->save()) {
      Utils::show($detalleGuia->getErrors(), true);
      throw new HttpException("No se puede guardar datos detalle guia");
      }
      }
      }

      $detalle_guia_delete_rc = empty($post["detalle_guia_delete_rc"]) ? [] : $post["detalle_guia_delete_rc"];

      foreach ($detalle_guia_delete_rc as $dgr) {
      if ($dgr["flg"] == 0) {
      $guia_remision_cliente = GuiaRemisionCliente::findOne($dgr["identificadorDetalle"]);
      $guia_remision_cliente->id_usuario_del = Yii::$app->user->getId();
      $guia_remision_cliente->fecha_del = Utils::getFechaActual();
      $guia_remision_cliente->ipmaq_del = Utils::obtenerIP();

      if (!$guia_remision_cliente->save()) {
      Utils::show($guia_remision_cliente->getErrors(), true);
      throw new HttpException("No se puede guardar datos guia remision cliente");
      }
      }
      }

      $transaction->commit();
      } catch (Exception $ex) {
      Utils::show($ex, true);
      $transaction->rollback();
      }

      // echo json_encode($guiaRemision->id_guia_remision);
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      Yii::$app->response->data = $guiaRemision->id_guia_remision;
      } else {
      throw new HttpException(404, 'The requested Item could not be found.');
      }
      }
     */

    public function actionDelete() {
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

            //echo json_encode($guiaRemision->id_guia_remision);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = $guiaRemision->id_guia_remision;
        } else {
            throw new HttpException(404, 'The requested Item could not be found.');
        }
    }

    public function actionLista() {

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
        foreach ($result as $k => $row) {
            $botones = '<a class="btn btn-icon btn-light-success btn-sm mr-2" href="solicitudatencionaux/default/editar/' . $row["id_atencion_pedidos"] . '"><i class="flaticon-edit"></i></a>';


            $data[] = [
                "fecha" => $row['fecha'],
                "nm_solicitud" => $row['nm_solicitud'],
                "hora_recojo" => $row['hora_recojo'],
                "razon_social"=>$row['razon_social'],
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

    public function actionExportar() {


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
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(str_replace('/web', '', Url::to('@webroot')) . '/assets/images/logo/logo_pais.jpg'); // put your path and image here
        //    $drawing->setCoordinates('A1');

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

        //$sheet->setCellValue('B4', 'REMITENTE:');

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


        /*       $sheet->setCellValue('A6', 'ITEM');
          $sheet->setCellValue('B6', 'DESTINO');
          $sheet->setCellValue('C6', 'CLIENTE');
          $sheet->setCellValue('D6', 'GUIA DE PEGASO');
          $sheet->setCellValue('E6', 'GUIA DEL CLIENTE');
          $sheet->setCellValue('F6', 'CANTIDAD');
          $sheet->setCellValue('G6', 'PESO');
          $sheet->setCellValue('H6', 'DESCRIPCION');
          $sheet->setCellValue('I6', 'VIA');
          $sheet->setCellValue('J6', 'EMPRESA/AGENCIA');
          $sheet->setCellValue('K6', 'FACTURA');
          $sheet->setCellValue('L6', 'GUIA');
          $sheet->setCellValue('M6', 'TOTAL');
         */

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

}
