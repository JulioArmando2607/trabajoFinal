<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido_cliente".
 *
 * @property int $id_pedido_cliente
 * @property int $id_cliente
 * @property int|null $id_remitente
 * @property string|null $nm_solicitud
 * @property string|null $id_tipo_unidad
 * @property int|null $stoka
 * @property int|null $estado_mercaderia
 * @property int|null $fragil
 * @property string|null $email
 * @property int|null $cantidad
 * @property float|null $peso
 * @property float|null $alto
 * @property float|null $ancho
 * @property float|null $largo
 * @property float|null $volumen
 * @property string|null $fecha
 * @property string|null $hora_recojo
 * @property int|null $tipo_servicio
 * @property int|null $id_distrito
 * @property int|null $id_direccion_recojo
 * @property string|null $contacto
 * @property int|null $id_area
 * @property string|null $referencia
 * @property string|null $telefono
 * @property int|null $cantidad_personal
 * @property string|null $observacion
 * @property string|null $notificacion
 * @property string|null $notificacion_descarga
 * @property string|null $tipo_pago
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string|null $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $id_estado
 */
class PedidoCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_usuario_reg', 'fecha_reg'], 'required'],
            [['id_cliente', 'id_remitente', 'stoka', 'estado_mercaderia', 'fragil', 'cantidad', 'tipo_servicio', 'id_distrito', 'id_direccion_recojo', 'id_area', 'cantidad_personal', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'id_estado'], 'integer'],
            [['peso', 'alto', 'ancho', 'largo', 'volumen'], 'number'],
            [['fecha', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['nm_solicitud', 'id_tipo_unidad', 'email', 'hora_recojo', 'contacto', 'referencia', 'telefono', 'observacion', 'notificacion', 'notificacion_descarga', 'tipo_pago', 'ipmaq_act'], 'string', 'max' => 255],
            [['ipmaq_reg', 'ipmaq_del'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pedido_cliente' => 'Id Pedido Cliente',
            'id_cliente' => 'Id Cliente',
            'id_remitente' => 'Id Remitente',
            'nm_solicitud' => 'Nm Solicitud',
            'id_tipo_unidad' => 'Id Tipo Unidad',
            'stoka' => 'Stoka',
            'estado_mercaderia' => 'Estado Mercaderia',
            'fragil' => 'Fragil',
            'email' => 'Email',
            'cantidad' => 'Cantidad',
            'peso' => 'Peso',
            'alto' => 'Alto',
            'ancho' => 'Ancho',
            'largo' => 'Largo',
            'volumen' => 'Volumen',
            'fecha' => 'Fecha',
            'hora_recojo' => 'Hora Recojo',
            'tipo_servicio' => 'Tipo Servicio',
            'id_distrito' => 'Id Distrito',
            'id_direccion_recojo' => 'Id Direccion Recojo',
            'contacto' => 'Contacto',
            'id_area' => 'Id Area',
            'referencia' => 'Referencia',
            'telefono' => 'Telefono',
            'cantidad_personal' => 'Cantidad Personal',
            'observacion' => 'Observacion',
            'notificacion' => 'Notificacion',
            'notificacion_descarga' => 'Notificacion Descarga',
            'tipo_pago' => 'Tipo Pago',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'id_estado' => 'Id Estado',
        ];
    }
}
