<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guia_remision".
 *
 * @property int $id_guia_remision
 * @property string|null $serie
 * @property int|null $numero_guia
 * @property string|null $fecha
 * @property string|null $fecha_traslado
 * @property int $id_via
 * @property int|null $id_tipo_via
 * @property int $id_cliente
 * @property int $id_agente
 * @property int $id_remitente
 * @property int $id_direccion_partida
 * @property int $id_destinatario
 * @property string $id_direccion_llegada
 * @property int $id_conductor
 * @property int $id_vehiculo
 * @property string|null $transportista
 * @property string|null $guia_remision_transportista
 * @property string|null $factura_transportista
 * @property float|null $importe_transportista
 * @property string|null $comentario_transportista
 * @property int $id_estado
 * @property string|null $comentario
 * @property string|null $observacion
 * @property int|null $id_archivo
 * @property int|null $id_archivo_inicio
 * @property int $id_usuario_reg
 * @property string $fecha_reg
 * @property string $ipmaq_reg
 * @property int|null $id_usuario_act
 * @property string|null $fecha_act
 * @property string|null $ipmaq_act
 * @property int|null $id_usuario_del
 * @property string|null $fecha_del
 * @property string|null $ipmaq_del
 * @property int|null $id_pedido_cliente
 * @property string|null $nm_solicitud
 * @property int|null $flg_liquidacion
 * @property int|null $flg_guia
 */
class GuiaRemision extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guia_remision';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_guia', 'id_via', 'id_tipo_via', 'id_cliente', 'id_agente', 'id_remitente', 'id_direccion_partida', 'id_destinatario', 'id_conductor', 'id_vehiculo', 'id_estado', 'id_archivo', 'id_archivo_inicio', 'id_usuario_reg', 'id_usuario_act', 'id_usuario_del', 'id_pedido_cliente', 'flg_liquidacion', 'flg_guia'], 'integer'],
            [['fecha', 'fecha_traslado', 'fecha_reg', 'fecha_act', 'fecha_del'], 'safe'],
            [['id_via', 'id_cliente', 'id_agente', 'id_remitente', 'id_direccion_partida', 'id_destinatario', 'id_direccion_llegada', 'id_conductor', 'id_vehiculo', 'id_estado', 'id_usuario_reg', 'fecha_reg', 'ipmaq_reg'], 'required'],
            [['importe_transportista'], 'number'],
            [['serie', 'ipmaq_reg', 'ipmaq_act', 'ipmaq_del'], 'string', 'max' => 20],
            [['id_direccion_llegada', 'comentario', 'observacion', 'nm_solicitud'], 'string', 'max' => 255],
            [['transportista'], 'string', 'max' => 200],
            [['guia_remision_transportista', 'factura_transportista', 'comentario_transportista'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_guia_remision' => 'Id Guia Remision',
            'serie' => 'Serie',
            'numero_guia' => 'Numero Guia',
            'fecha' => 'Fecha',
            'fecha_traslado' => 'Fecha Traslado',
            'id_via' => 'Id Via',
            'id_tipo_via' => 'Id Tipo Via',
            'id_cliente' => 'Id Cliente',
            'id_agente' => 'Id Agente',
            'id_remitente' => 'Id Remitente',
            'id_direccion_partida' => 'Id Direccion Partida',
            'id_destinatario' => 'Id Destinatario',
            'id_direccion_llegada' => 'Id Direccion Llegada',
            'id_conductor' => 'Id Conductor',
            'id_vehiculo' => 'Id Vehiculo',
            'transportista' => 'Transportista',
            'guia_remision_transportista' => 'Guia Remision Transportista',
            'factura_transportista' => 'Factura Transportista',
            'importe_transportista' => 'Importe Transportista',
            'comentario_transportista' => 'Comentario Transportista',
            'id_estado' => 'Id Estado',
            'comentario' => 'Comentario',
            'observacion' => 'Observacion',
            'id_archivo' => 'Id Archivo',
            'id_archivo_inicio' => 'Id Archivo Inicio',
            'id_usuario_reg' => 'Id Usuario Reg',
            'fecha_reg' => 'Fecha Reg',
            'ipmaq_reg' => 'Ipmaq Reg',
            'id_usuario_act' => 'Id Usuario Act',
            'fecha_act' => 'Fecha Act',
            'ipmaq_act' => 'Ipmaq Act',
            'id_usuario_del' => 'Id Usuario Del',
            'fecha_del' => 'Fecha Del',
            'ipmaq_del' => 'Ipmaq Del',
            'id_pedido_cliente' => 'Id Pedido Cliente',
            'nm_solicitud' => 'Nm Solicitud',
            'flg_liquidacion' => 'Flg Liquidacion',
            'flg_guia' => 'Flg Guia',
        ];
    }
}
